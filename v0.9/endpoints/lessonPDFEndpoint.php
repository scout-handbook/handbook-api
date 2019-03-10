<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;

use Skaut\OdyMarkdown\v0_9\OdyMarkdown;

$lessonPDFEndpoint = new Endpoint();

$getLessonPDF = function (Skautis $skautis, array $data, Endpoint $endpoint) use ($CONFIG) : void {
    $id = Helper::parseUuid($data['parent-id'], 'lesson');

    $name = '';
    if (!isset($data['caption']) || $data['caption'] === 'true') {
        $SQL = <<<SQL
SELECT name
FROM lessons
WHERE id = :id;
SQL;

        $db = new Database();
        $db->prepare($SQL);
        $idbytes = $id->getBytes();
        $db->bindParam(':id', $idbytes, PDO::PARAM_STR);
        $db->execute();
        $db->bindColumn('name', $name);
        $db->fetchRequire('lesson');
        unset($db);
        $name = strval($name);
    }


    $md = $endpoint->getParent()->call('GET', new Role('guest'), ['id' => $data['parent-id']])['response'];

    $html = '<body><h1>' . $name . '</h1>';
    $parser = new OdyMarkdown();
    $html .= $parser->parse($md);

    $html .= '</body>';

    $mpdf = new Mpdf([
        'fontDir' => [$CONFIG->basepath . '/Skaut/OdyMarkdown/v0_9/fonts/'],
        'fontdata' => [
            'odymarathon' => [
                'R' => 'OdyMarathon-Regular.ttf'
            ],
            'themix' => [
                'R' => 'TheMixC5-4_SemiLight.ttf',
                'I' => 'TheMixC5-4iSemiLightIta.ttf',
                'B' => 'TheMixC5-7_Bold.ttf',
                'BI' => 'TheMixC5-7iBoldItalic.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'default_font_size' => 8,
        'default_font' => 'themix',
        'format' => 'A5',
        'mirrorMargins' => true,
        'margin_top' => 12.5,
        'margin_left' => 19.5,
        'margin_right' => 12.25,
        'shrink_tables_to_fit' => 1,
        'use_kwt' => true
    ]);

    $qrRenderer = new ImageRenderer(
        new RendererStyle(90),
        new ImagickImageBackEnd()
    );
    $qrWriter = new Writer($qrRenderer);

    $mpdf->DefHTMLHeaderByName(
        'OddHeaderFirst',
        '<img class="QRheader" src="data:image/png;base64,' . base64_encode(
            $qrWriter->writeString($CONFIG->baseuri . '/lesson/' . $id->toString())
        ) . '">'
    );
    $mpdf->DefHTMLHeaderByName('OddHeader', '<div class="oddHeaderRight">' . $name . '</div>');
    $mpdf->DefHTMLFooterByName(
        'OddFooter',
        '<div class="oddFooterLeft">...jsme na jedné lodi</div>
        <img class="oddFooterRight" src="' . $CONFIG->basepath . '/Skaut/OdyMarkdown/v0_9/images/logo.svg' . '">'
    );
    $mpdf->DefHTMLFooterByName(
        'EvenFooter',
        '<div class="evenFooterLeft">Odyssea ' . date('Y') . '</div>
        <img class="evenFooterRight" src="' . $CONFIG->basepath . '/Skaut/OdyMarkdown/v0_9/images/ovce.svg' . '">'
    );

    if (!isset($data['qr']) || $data['qr'] === 'true') {
        $mpdf->SetHTMLHeaderByName('OddHeaderFirst', 'O');
    }
    $mpdf->SetHTMLFooterByName('OddFooter', 'O');
    $mpdf->SetHTMLFooterByName('EvenFooter', 'E');

    $mpdf->WriteHTML('', 2);
    $mpdf->SetHTMLHeaderByName('OddHeader', 'O');

    $mpdf->WriteHTML(file_get_contents($CONFIG->basepath . '/Skaut/OdyMarkdown/v0_9/styles.php') ?: '', 1);
    $mpdf->WriteHTML($html, 2);

    header('content-type:application/pdf; charset=utf-8');
    $mpdf->Output(
        $id->toString() . '_' . Helper::urlEscape($name) . '.pdf',
        Destination::INLINE
    );
};
$lessonPDFEndpoint->setListMethod(new Role('editor'), $getLessonPDF);
