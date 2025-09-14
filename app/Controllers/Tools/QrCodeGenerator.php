<?php

namespace App\Controllers\Tools;

use App\Controllers\BaseController;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;


class QrCodeGenerator extends BaseController
{
    private function validateInput()
    {
        $data = inputGet('data');
        $scale = inputGet('scale');
        if (trim($scale) === "")
            $scale = null;
        if (!$data or !is_string($data) or empty($data))
            return 'Invalid Input Data';
        if (!is_null($scale) and !is_numeric($scale))
            return 'Scale paramter must be a valid number.';
        if (!is_null($scale) and ($scale < 1 or $scale > 30))
            return 'Scale should be in range of 1 to 30.';
        return ['data' => $data, 'scale' => $scale];
    }

    public function index()
    {
        try {
            session()->close();

            if (is_string($input = $this->validateInput())) {
                return $this->response->setStatusCode(400)->setBody($input); // $input here is validation error message string
            }

            $data = $input['data'];
            $scale = (int) ($input['scale'] ?? 8);
            // Set options
            $options = new QROptions();
            $options->version = QRCode::VERSION_AUTO;
            $options->outputBase64 = false;
            $options->eccLevel = QRCode::ECC_L;
            $options->outputType = QRCode::OUTPUT_IMAGE_PNG;
            $options->scale = $scale;
            $options->quietzoneSize = 1;
            $options->quality = 90;


            // Generate QR code
            $image = (new QRCode($options))->render($data);

            // Output the QR code as a PNG image
            return $this->response
                ->setContentType('image/png')
                ->setBody($image)
                ->send();

        } finally {
            session()->start();
        }
    }
}
