<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private const SECTIONS = [
        'identity' => ['label' => 'Identité du site', 'fields' => [
            'site_name' => ['label' => 'Nom du site', 'type' => 'text'],
            'site_name_accent' => ['label' => 'Mot mis en couleur (ex: FOOD)', 'type' => 'text'],
            'site_logo' => ['label' => 'Logo (chemin ou URL)', 'type' => 'logo'],
            'site_description' => ['label' => 'Description courte', 'type' => 'textarea'],
            'footer_credit' => ['label' => 'Crédit footer', 'type' => 'text'],
        ]],
        'hero' => ['label' => 'Section Hero', 'fields' => [
            'hero_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'hero_title' => ['label' => 'Titre principal', 'type' => 'text'],
            'hero_description' => ['label' => 'Description', 'type' => 'textarea'],
            'hero_cta' => ['label' => 'Texte du bouton', 'type' => 'text'],
        ]],
        'bestseller' => ['label' => 'Best-sellers', 'fields' => [
            'bestseller_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'bestseller_title' => ['label' => 'Titre', 'type' => 'text'],
            'bestseller_desc' => ['label' => 'Description', 'type' => 'text'],
            'bestseller_cta' => ['label' => 'Texte du bouton', 'type' => 'text'],
        ]],
        'steps' => ['label' => 'Comment ça marche', 'fields' => [
            'steps_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'steps_title' => ['label' => 'Titre', 'type' => 'text'],
            'steps_desc' => ['label' => 'Description', 'type' => 'text'],
        ]],
        'about' => ['label' => 'Section À propos (accueil)', 'fields' => [
            'about_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'about_title' => ['label' => 'Titre', 'type' => 'text'],
            'about_text_1' => ['label' => 'Paragraphe 1', 'type' => 'textarea'],
            'about_text_2' => ['label' => 'Paragraphe 2', 'type' => 'textarea'],
        ]],
        'stats' => ['label' => 'Statistiques', 'fields' => [
            'stat_1_value' => ['label' => 'Stat 1 — valeur', 'type' => 'text'],
            'stat_1_label' => ['label' => 'Stat 1 — label', 'type' => 'text'],
            'stat_2_value' => ['label' => 'Stat 2 — valeur', 'type' => 'text'],
            'stat_2_label' => ['label' => 'Stat 2 — label', 'type' => 'text'],
            'stat_3_value' => ['label' => 'Stat 3 — valeur', 'type' => 'text'],
            'stat_3_label' => ['label' => 'Stat 3 — label', 'type' => 'text'],
        ]],
        'testimonials' => ['label' => 'Témoignages', 'fields' => [
            'testimonials_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'testimonials_title' => ['label' => 'Titre', 'type' => 'text'],
            'testimonials_desc' => ['label' => 'Description', 'type' => 'text'],
        ]],
        'locations' => ['label' => 'Adresses (accueil)', 'fields' => [
            'locations_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'locations_title' => ['label' => 'Titre', 'type' => 'text'],
            'locations_desc' => ['label' => 'Description', 'type' => 'text'],
        ]],
        'social' => ['label' => 'Réseaux sociaux', 'fields' => [
            'facebook_url' => ['label' => 'Facebook URL', 'type' => 'text'],
            'instagram_url' => ['label' => 'Instagram URL', 'type' => 'text'],
            'tiktok_url' => ['label' => 'TikTok URL', 'type' => 'text'],
            'social_cta' => ['label' => 'Texte CTA social', 'type' => 'text'],
        ]],
        'menu' => ['label' => 'Page Menu', 'fields' => [
            'menu_eyebrow' => ['label' => 'Sur-titre', 'type' => 'text'],
            'menu_title' => ['label' => 'Titre', 'type' => 'text'],
            'menu_desc' => ['label' => 'Description', 'type' => 'text'],
        ]],
        'about_page' => ['label' => 'Page À propos', 'fields' => [
            'about_page_hero' => ['label' => 'Titre hero', 'type' => 'text'],
            'about_page_subtitle' => ['label' => 'Sous-titre', 'type' => 'textarea'],
            'about_page_philosophy' => ['label' => 'Philosophie', 'type' => 'textarea'],
        ]],
        'contact' => ['label' => 'Page Contact', 'fields' => [
            'contact_hero' => ['label' => 'Titre hero', 'type' => 'text'],
            'contact_subtitle' => ['label' => 'Sous-titre', 'type' => 'text'],
        ]],
        'footer' => ['label' => 'Footer', 'fields' => [
            'footer_text' => ['label' => 'Copyright', 'type' => 'text'],
        ]],
    ];

    public function index()
    {
        $values = SiteSetting::pluck('value', 'key');
        return view('admin.settings.index', [
            'sections' => self::SECTIONS,
            'values' => $values,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings'      => 'required|array',
            'settings.*'    => 'nullable|string',
            'logo_upload'   => 'nullable|image|max:2048',
        ]);

        // Handle logo file upload
        if ($request->hasFile('logo_upload') && $request->file('logo_upload')->isValid()) {
            $file = $request->file('logo_upload');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['settings']['site_logo'] = 'images/' . $filename;
        }

        foreach ($data['settings'] as $key => $value) {
            $group = 'general';
            foreach (self::SECTIONS as $g => $section) {
                if (array_key_exists($key, $section['fields'])) {
                    $group = $g;
                    break;
                }
            }
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '', 'group' => $group]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour.');
    }
}
