<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Output\Destination;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;

use Skaut\OdyMarkdown\v1_0\OdyMarkdown;

require_once($CONFIG->basepath . '/v1.0/endpoints/fieldEndpoint.php');

$lessonPDFEndpoint = new Endpoint();

$getLessonPDF = function (Skautis $skautis, array $data, Endpoint $endpoint) use ($CONFIG, $fieldEndpoint): array {
    $id = Helper::parseUuid($data['parent-id'], 'lesson');

    $name = '';
    if (!isset($data['caption']) || $data['caption'] === 'true') {
        $SQL = <<<SQL
SELECT `name`
FROM `lessons`
WHERE `id` = :id;
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
    $icon = '00000000-0000-0000-0000-000000000000';
    $fields = $fieldEndpoint->call('GET', new Role('editor'), ['override-group' => true])['response'];
    foreach ($fields as $field) {
        if ($field->containsLesson($id)) {
            $icon = $field->getIcon()->toString();
        }
    }

    $html = '<body><h1 class="lesson-name">' . $name . '</h1>';
    $parser = new OdyMarkdown();
    $html .= $parser->parse($md);
    $html .= '</body>';

    $qrCode = new QrCode($CONFIG->baseuri . '/lesson/' . $id->toString());
    $qrCode->disableBorder();
    $qrOutput = new Output\Svg();

    $mpdf = new Mpdf([
        'fontDir' => [$CONFIG->basepath . '/Skaut/OdyMarkdown/v1_0/fonts/'],
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

    $mpdf->DefHTMLHeaderByName(
        'OddHeaderFirst',
        // Substr removes <?xml tag
        '<div class="QRheader">' . mb_substr($qrOutput->output($qrCode, 50), 21) . '</div>'
    );
    $mpdf->DefHTMLHeaderByName('OddHeader', '<div class="oddHeaderRight">' . $name . '</div>');
    if ($icon !== '00000000-0000-0000-0000-000000000000') {
        $mpdf->DefHTMLFooterByName(
            'OddFooter',
            '<img class="oddFooterRight" src="' . $CONFIG->imagepath . '/original/' . $icon . '.jpg">'
        );
    }

    if (!isset($data['qr']) || $data['qr'] === 'true') {
        $mpdf->SetHTMLHeaderByName('OddHeaderFirst', 'O');
    }
    $mpdf->SetHTMLFooterByName('OddFooter', 'O');
    $mpdf->SetHTMLFooterByName('EvenFooter', 'E');

    $mpdf->WriteHTML('', HTMLParserMode::HTML_BODY);
    $mpdf->SetHTMLHeaderByName('OddHeader', 'O');

    $mpdf->WriteHTML(
        file_get_contents($CONFIG->basepath . '/Skaut/OdyMarkdown/v1_0/styles.css') ?: '',
        HTMLParserMode::HEADER_CSS
    );
    $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

    header('content-type:application/pdf; charset=utf-8');
    $mpdf->Output(
        $id->toString() . '_' . Helper::urlEscape($name) . '.pdf',
        Destination::INLINE
    );
    return ['status' => 200];
};
$lessonPDFEndpoint->setListMethod(new Role('editor'), $getLessonPDF);
