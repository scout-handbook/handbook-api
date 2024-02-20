<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Output\Destination;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;
use Ramsey\Uuid\UuidInterface;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;

use Skaut\OdyMarkdown\v1_0\OdyMarkdown;

require_once($CONFIG->basepath . '/v1.0/endpoints/competenceEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/fieldEndpoint.php');

$lessonPDFEndpoint = new Endpoint();

$iconFooter = function (UuidInterface $lessonId, array $lessonCompetences) use ($CONFIG, $competenceEndpoint, $fieldEndpoint) {
    $icon = '00000000-0000-0000-0000-000000000000';
    $fields = $fieldEndpoint->call('GET', new Role('editor'), ['override-group' => true])['response'];
    foreach ($fields as $field) {
        if ($field->containsLesson($lessonId)) {
            $icon = $field->getIcon()->toString();
        }
    }

    $allCompetences = $competenceEndpoint->call('GET', new Role('guest'), [])['response'];
    $competenceNumbers = array_map(
        function (UuidInterface $competence) use ($allCompetences) {
            return $allCompetences[$competence->toString()]->getNumber();
        },
        $lessonCompetences
    );
    // TODO: Sort

    $ret = '';
    $rightOffset = 8;

    if ($icon !== '00000000-0000-0000-0000-000000000000') {
        $ret .= '<div class="footer-item" style="right: ' . $rightOffset . 'mm"><img src="' . $CONFIG->imagepath . '/original/' . $icon . '.jpg"></div>';
        $rightOffset += 14;
    }

    foreach($competenceNumbers as $competence) {
        $ret .= '<div class="footer-item footer-competence" style="right: ' . $rightOffset . 'mm">' . $competence . '</div>';
        $rightOffset += 14;
    }

    return $ret;
};

$getLessonPDF = function (Skautis $skautis, array $data, Endpoint $endpoint) use ($CONFIG, $iconFooter): array {
    $id = Helper::parseUuid($data['parent-id'], 'lesson');

    $lessonMetadata = $endpoint->getParent()->call('GET', new Role('editor'), ['override-group' => true])['response'][$data['parent-id']];

    $md = $endpoint->getParent()->call('GET', new Role('guest'), ['id' => $data['parent-id']])['response'];
    $html = '<body><h1 class="lesson-name">' . $lessonMetadata->getName() . '</h1>';
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
        'FirstPageQRCodeHeader',
        // Substr removes <?xml tag
        '<div class="first-page-qr-code-header">' . mb_substr($qrOutput->output($qrCode, 50), 21) . '</div>'
    );
    $mpdf->DefHTMLHeaderByName('OddPageLessonNameHeader', '<div class="odd-page-lesson-name-header">' . $lessonMetadata->getName() . '</div>');
    $mpdf->DefHTMLFooterByName(
        'EvenPageFooter',
        '<div class="footer-clear">&nbsp;</div>'
    );
    $mpdf->DefHTMLFooterByName(
        'OddPageFooter',
        '<div class="footer-clear">&nbsp;</div>' .
        $iconFooter($id, $lessonMetadata->getCompetences())
    );

    if (!isset($data['qr']) || $data['qr'] === 'true') {
        $mpdf->SetHTMLHeaderByName('FirstPageQRCodeHeader', 'O');
    }
    $mpdf->SetHTMLFooterByName('EvenPageFooter', 'E');
    $mpdf->SetHTMLFooterByName('OddPageFooter', 'O');

    $mpdf->WriteHTML('', HTMLParserMode::HTML_BODY);
    $mpdf->SetHTMLHeaderByName('OddPageLessonNameHeader', 'O');

    $mpdf->WriteHTML(
        file_get_contents($CONFIG->basepath . '/Skaut/OdyMarkdown/v1_0/styles.css') ?: '',
        HTMLParserMode::HEADER_CSS
    );
    $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

    header('content-type:application/pdf; charset=utf-8');
    $mpdf->Output(
        Helper::urlEscape($lessonMetadata->getName()) . '_' . $id->toString() . '.pdf',
        Destination::INLINE
    );
    return ['status' => 200];
};
$lessonPDFEndpoint->setListMethod(new Role('editor'), $getLessonPDF);
