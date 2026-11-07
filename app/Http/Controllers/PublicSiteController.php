<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Lead;
use App\Models\MarketplaceRule;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Template;
use App\Models\User;
use App\Services\Ai\FakeListingGenerator;
use App\Services\ModuleManager\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicSiteController extends Controller
{
    public function home()
    {
        $modules = ModuleRegistry::all();
        $plans = Plan::where('is_active', true)->get();
        return view('public.home', compact('modules', 'plans'));
    }

    public function generateDemoSample(Request $request)
    {
        $productName = $request->input('name', 'Wireless Noise-Cancelling Earbuds Pro');
        $marketplace = $request->input('marketplace', 'amazon');
        $locale = $request->input('locale', 'en');

        // Create temporary product instance in memory
        $tempProduct = new Product([
            'name' => $productName,
            'price' => 149.99,
            'currency' => 'USD',
        ]);

        $generated = FakeListingGenerator::generate($tempProduct, [
            'marketplace' => $marketplace,
            'locale' => $locale,
        ]);

        return response()->json([
            'success' => true,
            'data' => $generated,
        ]);
    }

    public function features()
    {
        $modules = ModuleRegistry::all();
        return view('public.features', compact('modules'));
    }

    public function moduleDetail(string $key)
    {
        $modules = ModuleRegistry::all();
        if (!isset($modules[$key])) {
            abort(404, "Module {$key} not found.");
        }

        $module = $modules[$key];
        $module['key'] = $key;
        $plans = Plan::where('is_active', true)->get();

        return view('public.module-detail', compact('module', 'plans'));
    }

    public function marketplaces()
    {
        $rules = MarketplaceRule::all();
        return view('public.marketplaces', compact('rules'));
    }

    public function marketplaceDetail(string $code)
    {
        $rule = MarketplaceRule::where('marketplace_code', $code)->first();
        if (!$rule) {
            abort(404);
        }
        return view('public.marketplace-detail', compact('rule'));
    }

    public function solutions(string $persona = 'solo')
    {
        $presets = ModuleRegistry::presets();
        if (!isset($presets[$persona])) {
            $persona = 'solo';
        }

        $preset = $presets[$persona];
        $allModules = ModuleRegistry::all();

        return view('public.solutions', compact('preset', 'persona', 'allModules', 'presets'));
    }

    public function pricing()
    {
        $plans = Plan::where('is_active', true)->get();
        $modules = ModuleRegistry::all();
        return view('public.pricing', compact('plans', 'modules'));
    }

    public function demo()
    {
        return view('public.demo');
    }

    public function demoLogin(string $role = 'owner')
    {
        $email = match ($role) {
            'superadmin' => 'superadmin@demo.test',
            'support' => 'support@demo.test',
            'finance' => 'finance@demo.test',
            'moderator' => 'moderator@demo.test',
            'sales' => 'sales@demo.test',
            'owner' => 'owner@demo.test',
            'admin' => 'admin@demo.test',
            'manager' => 'manager@demo.test',
            'writer' => 'writer@demo.test',
            'seo' => 'seo@demo.test',
            'translator' => 'translator@demo.test',
            'reviewer' => 'reviewer@demo.test',
            'operator' => 'operator@demo.test',
            'accountant' => 'accountant@demo.test',
            'hr' => 'hr@demo.test',
            'developer' => 'developer@demo.test',
            'viewer' => 'viewer@demo.test',
            'client' => 'client@demo.test',
            'partner' => 'partner@demo.test',
            default => 'owner@demo.test',
        };

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('demo')->with('error', "Demo user {$email} not seeded yet. Please run php artisan db:seed first.");
        }

        Auth::login($user);

        // Redirect to relevant panel
        if ($user->isPlatformStaff()) {
            return redirect('/superadmin');
        } elseif ($role === 'client') {
            return redirect('/client');
        } elseif ($role === 'partner') {
            return redirect('/partner');
        }

        $workspace = $user->workspaces()->first() ?? $user->currentWorkspace;
        if ($workspace) {
            return redirect("/app/{$workspace->slug}");
        }

        return redirect('/app');
    }

    public function templatesGallery()
    {
        $templates = Template::where('is_active', true)->get();
        return view('public.templates-gallery', compact('templates'));
    }

    public function marketplaceRules()
    {
        $rules = MarketplaceRule::where('is_active', true)->get();
        return view('public.rules-library', compact('rules'));
    }

    public function blog()
    {
        $articles = Article::where('status', 'published')->orderByDesc('created_at')->paginate(9);
        return view('public.blog', compact('articles'));
    }

    public function blogPost(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $article->increment('views_count');
        return view('public.blog-post', compact('article'));
    }

    public function knowledgeBase()
    {
        $categories = ArticleCategory::with('articles')->get();
        return view('public.knowledge-base', compact('categories'));
    }

    public function apiDocs()
    {
        return view('public.api-docs');
    }

    public function changelog()
    {
        return view('public.changelog');
    }

    public function status()
    {
        return view('public.status');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string',
        ]);

        Lead::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'status' => 'new',
            'source' => 'public_contact_form',
            'notes' => $data['message'],
        ]);

        return back()->with('success', 'Thank you! Your message has been received and our team will get in touch shortly.');
    }

    public function legalPage(string $page)
    {
        $allowed = ['about', 'careers', 'partners', 'reseller', 'security', 'compliance', 'privacy', 'terms', 'cookie', 'refund', 'dpa'];
        if (!in_array($page, $allowed, true)) {
            abort(404);
        }

        return view("public.legal.{$page}", ['page' => $page]);
    }

    public function sitemapXml()
    {
        $modules = ModuleRegistry::all();
        $articles = Article::where('status', 'published')->get();

        return response()->view('public.sitemap', compact('modules', 'articles'))
            ->header('Content-Type', 'text/xml');
    }

    public function robotsTxt()
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /superadmin\nDisallow: /app\nDisallow: /client\nDisallow: /partner\n\nSitemap: " . url('sitemap.xml') . "\n";
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
