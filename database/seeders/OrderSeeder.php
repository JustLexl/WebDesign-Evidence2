<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderEvidence;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $salesUser = User::where('email', 'sales@halcon.com')->first();
        $routeUser = User::where('email', 'route@halcon.com')->first();

        $uploadDir = public_path('uploads/evidences');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $routeImgPath = 'uploads/evidences/route_inv1001.jpg';
        $deliveredImgPath = 'uploads/evidences/delivered_inv1001.jpg';
        $routeImgPath2 = 'uploads/evidences/route_inv1002.jpg';

        $this->generateSampleImage(public_path($routeImgPath), 'LOADED UNIT - INV-1001');
        $this->generateSampleImage(public_path($deliveredImgPath), 'DELIVERED EVIDENCE - INV-1001');
        $this->generateSampleImage(public_path($routeImgPath2), 'LOADED UNIT - INV-1002');

        $order1 = Order::updateOrCreate(
            ['invoice_number' => 'INV-1001'],
            [
                'customer_number' => 'CUST-001',
                'customer_name' => 'Constructora Monterrey S.A.',
                'fiscal_data' => 'RFC: CMO980112XX1, Av. Constitucion 450, Monterrey, N.L.',
                'order_datetime' => Carbon::now()->subDays(3),
                'delivery_address' => 'Av. Lazaro Cardenas 2201, San Pedro Garza Garcia',
                'notes' => 'Deliver at rear entrance before 5 PM',
                'status' => 'Delivered',
                'user_id' => $salesUser ? $salesUser->id : null,
                'process_date' => Carbon::now()->subDays(2),
                'route_photo' => $routeImgPath,
                'delivery_photo' => $deliveredImgPath,
            ]
        );

        OrderEvidence::updateOrCreate(
            ['order_id' => $order1->id, 'stage' => 'In route'],
            [
                'user_id' => $routeUser ? $routeUser->id : null,
                'photo_path' => $routeImgPath,
                'notes' => 'Unit fully loaded and inspected by warehouse supervisor',
            ]
        );

        OrderEvidence::updateOrCreate(
            ['order_id' => $order1->id, 'stage' => 'Delivered'],
            [
                'user_id' => $routeUser ? $routeUser->id : null,
                'photo_path' => $deliveredImgPath,
                'notes' => 'Materials delivered and signed by site manager',
            ]
        );

        $order2 = Order::updateOrCreate(
            ['invoice_number' => 'INV-1002'],
            [
                'customer_number' => 'CUST-002',
                'customer_name' => 'Edificaciones del Norte',
                'fiscal_data' => 'RFC: EDN150320AB2, Calle Hidalgo 120, Guadalupe, N.L.',
                'order_datetime' => Carbon::now()->subDays(1),
                'delivery_address' => 'Carretera Nacional Km 250, Santiago, N.L.',
                'notes' => 'Concrete blocks and Portland cement packages',
                'status' => 'In route',
                'user_id' => $salesUser ? $salesUser->id : null,
                'process_date' => Carbon::now()->subHours(12),
                'route_photo' => $routeImgPath2,
                'delivery_photo' => null,
            ]
        );

        OrderEvidence::updateOrCreate(
            ['order_id' => $order2->id, 'stage' => 'In route'],
            [
                'user_id' => $routeUser ? $routeUser->id : null,
                'photo_path' => $routeImgPath2,
                'notes' => 'Loaded unit departing central warehouse',
            ]
        );

        Order::updateOrCreate(
            ['invoice_number' => 'INV-1003'],
            [
                'customer_number' => 'CUST-003',
                'customer_name' => 'Obras y Desarrollos Urbanos',
                'fiscal_data' => 'RFC: ODU080914TT3, Blvd. Diaz Ordaz 890, Santa Catarina',
                'order_datetime' => Carbon::now()->subHours(8),
                'delivery_address' => 'Parque Industrial Milimex, Apodaca',
                'notes' => 'Requires 50 corrugated bars 3/8, coordinating purchase of additional stock',
                'status' => 'In process',
                'user_id' => $salesUser ? $salesUser->id : null,
                'process_date' => Carbon::now()->subHours(4),
                'route_photo' => null,
                'delivery_photo' => null,
            ]
        );

        Order::updateOrCreate(
            ['invoice_number' => 'INV-1004'],
            [
                'customer_number' => 'CUST-004',
                'customer_name' => 'Constructora Atlas',
                'fiscal_data' => 'RFC: CAT120411ZZ4, Pino Suarez 330, Monterrey',
                'order_datetime' => Carbon::now()->subHours(2),
                'delivery_address' => 'Colonia Vista Hermosa, Monterrey',
                'notes' => 'New order received by telephone',
                'status' => 'Ordered',
                'user_id' => $salesUser ? $salesUser->id : null,
                'process_date' => null,
                'route_photo' => null,
                'delivery_photo' => null,
            ]
        );

        $archivedOrder = Order::withTrashed()->updateOrCreate(
            ['invoice_number' => 'INV-1005'],
            [
                'customer_number' => 'CUST-005',
                'customer_name' => 'Urbanizaciones Delta',
                'fiscal_data' => 'RFC: UDE050607LL5, Av. Universidad 100, San Nicolas',
                'order_datetime' => Carbon::now()->subDays(5),
                'delivery_address' => 'Av. Ruiz Cortines 1400, Monterrey',
                'notes' => 'Order canceled by customer due to budget adjustment',
                'status' => 'Ordered',
                'user_id' => $salesUser ? $salesUser->id : null,
                'process_date' => null,
                'route_photo' => null,
                'delivery_photo' => null,
            ]
        );

        if (!$archivedOrder->trashed()) {
            $archivedOrder->delete();
        }
    }

    private function generateSampleImage($path, $text)
    {
        if (file_exists($path)) {
            return;
        }

        $image = imagecreatetruecolor(640, 360);
        $bgColor = imagecolorallocate($image, 33, 37, 41);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $accentColor = imagecolorallocate($image, 13, 110, 253);

        imagefill($image, 0, 0, $bgColor);
        imagerectangle($image, 10, 10, 629, 349, $accentColor);
        imagerectangle($image, 12, 12, 627, 347, $accentColor);

        imagestring($image, 5, 40, 160, $text, $textColor);
        imagestring($image, 3, 40, 190, 'HALCON CONSTRUCTION DISTRIBUTOR', $accentColor);
        imagestring($image, 2, 40, 215, 'Timestamp: ' . date('Y-m-d H:i:s'), $textColor);

        imagejpeg($image, $path, 90);
        imagedestroy($image);
    }
}
