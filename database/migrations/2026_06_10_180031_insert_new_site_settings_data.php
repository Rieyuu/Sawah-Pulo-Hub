
<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SiteSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        SiteSetting::setValue(
            'hero_title',
            'Keindahan Alam Pedesaan & Edukasi Pertanian',
            'text'
        );

        SiteSetting::setValue(
            'hero_subtitle',
            'Rasakan pengalaman edukatif bercocok tanam hidroponik, budidaya ternak, dan keindahan panorama sawah hijau yang menenangkan jiwa.',
            'textarea'
        );

        SiteSetting::setValue(
            'hero_bg_image',
            '/images/sawah_pulo_background.png',
            'image'
        );

        SiteSetting::setValue(
            'footer_description',
            'Destinasi wisata alam pedesaan yang menyajikan keindahan alam persawahan dengan berbagai fasilitas menarik, nyaman, dan edukatif.',
            'textarea'
        );

        SiteSetting::setValue(
            'payment_bank_name',
            'Bank Mandiri',
            'text'
        );

        SiteSetting::setValue(
            'payment_bank_account',
            '1420012345678',
            'text'
        );

        SiteSetting::setValue(
            'payment_bank_recipient',
            'BUMDes Sawah Pulo',
            'text'
        );

        SiteSetting::setValue(
            'about_image',
            '/images/sawah_pulo_background.png',
            'image'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::whereIn('key', [
            'hero_title',
            'hero_subtitle',
            'hero_bg_image',
            'footer_description',
            'payment_bank_name',
            'payment_bank_account',
            'payment_bank_recipient',
            'about_image'
        ])->delete();
    }
};
