<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeAssets extends Command
{
    protected $signature = 'assets:optimize';
    protected $description = 'Optimize CSS and JS assets for better performance';

    public function handle()
    {
        $this->info('Starting asset optimization...');
        
        // Create optimized directory
        $optimizedDir = public_path('assets/optimized');
        if (!File::exists($optimizedDir)) {
            File::makeDirectory($optimizedDir, 0755, true);
        }
        
        // Optimize CSS
        $this->optimizeCSS();
        
        // Optimize JS
        $this->optimizeJS();
        
        // Create critical CSS
        $this->createCriticalCSS();
        
        $this->info('Asset optimization completed!');
    }
    
    private function optimizeCSS()
    {
        $this->info('Optimizing CSS files...');
        
        $cssFiles = [
            'matrix.css',
            'responsive.css',
            'font-awesome-fix.css',
            'optimized.css'
        ];
        
        $combinedCSS = '';
        
        foreach ($cssFiles as $file) {
            $path = public_path("css/{$file}");
            if (File::exists($path)) {
                $content = File::get($path);
                // Basic minification
                $content = $this->minifyCSS($content);
                $combinedCSS .= $content . "\n";
            }
        }
        
        // Save combined and minified CSS
        File::put(public_path('assets/optimized/app.min.css'), $combinedCSS);
        
        $this->line('✓ Combined CSS files into app.min.css');
    }
    
    private function optimizeJS()
    {
        $this->info('Optimizing JS files...');
        
        $jsFiles = [
            'mobile-gestures.js',
            'notification-system.js',
            'online-status.js'
        ];
        
        $combinedJS = '';
        
        foreach ($jsFiles as $file) {
            $path = public_path("js/{$file}");
            if (File::exists($path)) {
                $content = File::get($path);
                // Basic minification
                $content = $this->minifyJS($content);
                $combinedJS .= $content . ";\n";
            }
        }
        
        // Save combined and minified JS
        File::put(public_path('assets/optimized/app.min.js'), $combinedJS);
        
        $this->line('✓ Combined JS files into app.min.js');
    }
    
    private function createCriticalCSS()
    {
        $this->info('Creating critical CSS...');
        
        // Critical CSS for above-the-fold content
        $criticalCSS = '
        /* Critical CSS - Above the fold */
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .navbar { background: #00073d; color: white; padding: 1rem; }
        .page-header { background: linear-gradient(135deg, #00073d, #001a5c); color: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 0.5rem 1rem; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; }
        .btn-primary { background: #00073d; color: white; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #dee2e6; }
        ';
        
        File::put(public_path('assets/optimized/critical.css'), $this->minifyCSS($criticalCSS));
        
        $this->line('✓ Created critical.css for above-the-fold content');
    }
    
    private function minifyCSS($css)
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/;\s*}/', '}', $css);
        $css = preg_replace('/\s*{\s*/', '{', $css);
        $css = preg_replace('/;\s*/', ';', $css);
        $css = preg_replace('/,\s*/', ',', $css);
        $css = preg_replace('/:\s*/', ':', $css);
        
        return trim($css);
    }
    
    private function minifyJS($js)
    {
        // Remove single-line comments
        $js = preg_replace('~//.*~', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        $js = preg_replace('/;\s*/', ';', $js);
        $js = preg_replace('/{\s*/', '{', $js);
        $js = preg_replace('/}\s*/', '}', $js);
        
        return trim($js);
    }
}
