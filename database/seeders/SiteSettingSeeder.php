<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tentang Kami Settings
        SiteSetting::setValue(
            'about_history',
            'Sawah-Pulo-Hub didirikan pada tahun 2024 sebagai inisiatif eduwisata ramah lingkungan yang memadukan pertanian tradisional dengan inovasi modern. Terletak di kawasan asri, objek wisata ini bertujuan mengedukasi generasi muda mengenai pentingnya kedaulatan pangan, bercocok tanam secara hidroponik dan organik, serta budidaya hewan ternak secara sehat.',
            'textarea'
        );

        SiteSetting::setValue(
            'about_vision',
            'Menjadi pusat edukasi pertanian, perkebunan, dan peternakan terkemuka di Indonesia yang menginspirasi gaya hidup berkelanjutan.',
            'textarea'
        );

        SiteSetting::setValue(
            'about_mission',
            "1. Menyediakan wahana pembelajaran interaktif di bidang agrobisnis bagi pelajar dan umum.\n2. Melestarikan metode pertanian lokal yang dipadukan dengan teknik ramah lingkungan.\n3. Mengembangkan ekosistem pariwisata edukatif yang memberikan dampak ekonomi positif bagi masyarakat sekitar.",
            'textarea'
        );

        SiteSetting::setValue(
            'about_structure_image',
            '/images/structure-placeholder.svg',
            'image'
        );

        // Site Plan Setting
        SiteSetting::setValue(
            'site_plan_image',
            '/images/site-plan-placeholder.svg',
            'image'
        );

        // Jam Operasional Wisata
        SiteSetting::setValue(
            'operating_days',
            'Senin - Minggu',
            'text'
        );

        SiteSetting::setValue(
            'operating_hours',
            '08:00 - 17:00 WIB',
            'text'
        );

        // Kontak & Sosial Media Settings
        SiteSetting::setValue(
            'contact_address',
            'Dusun Pulo, Kec. Sawah Indah, Kab. Mojokerto, Jawa Timur, Indonesia',
            'textarea'
        );

        SiteSetting::setValue(
            'contact_whatsapp',
            '6281234567890', // Format nomor WA internasional untuk chat link
            'text'
        );

        SiteSetting::setValue(
            'contact_instagram',
            'https://instagram.com/sawahpulohub',
            'text'
        );

        SiteSetting::setValue(
            'contact_tiktok',
            'https://tiktok.com/@sawahpulohub',
            'text'
        );

        SiteSetting::setValue(
            'contact_x',
            'https://x.com/sawahpulohub',
            'text'
        );

        // Maps Settings (Tautan Utama & Link Embed)
        SiteSetting::setValue(
            'contact_maps_url',
            'https://maps.app.goo.gl/PuloHubWisataEdukasiSample',
            'text'
        );

        SiteSetting::setValue(
            'contact_maps_embed',
            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15822.428458925574!2d112.5028479!3d-7.5414969!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78e1b1b1b1b1b1%3A0x1b1b1b1b1b1b1b1b!2sMojokerto%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid',
            'text'
        );
    }
}
