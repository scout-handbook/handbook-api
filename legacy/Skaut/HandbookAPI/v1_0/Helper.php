<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;
use Skaut\HandbookAPI\v1_0\Exception\RoleException;
use Skaut\HandbookAPI\v1_0\Exception\SkautISException;
use Skautis\Skautis;

@_API_EXEC === 1 or exit('Restricted access.');

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
class Helper // Helper functions
{
    public static function parseUuid(string $id, string $resourceName): UuidInterface
    {
        try {
            return Uuid::fromString($id);
        } catch (InvalidUuidStringException $e) {
            throw new NotFoundException($resourceName);
        }
    }

    public static function xssSanitize(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function skautisTry(callable $callback, bool $hardCheck = true)
    {
        $_API_SECRETS_EXEC = 1;
        $SECRETS = require $_SERVER['DOCUMENT_ROOT'].'/api-secrets.php';
        $skautis = Skautis::getInstance($SECRETS->skautis_app_id, $SECRETS->skautis_test_mode);
        if (isset($_COOKIE['skautis_token']) and isset($_COOKIE['skautis_timeout'])) {
            $dateLogout = \DateTime::createFromFormat('U', $_COOKIE['skautis_timeout']);
            if (! $dateLogout) {
                $dateLogout = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new \DateInterval('PT10M'));
            }
            $dateLogout = $dateLogout->setTimezone(new \DateTimeZone('Europe/Prague'))->format('j. n. Y H:i:s');
            $reconstructedPost = [
                'skautIS_Token' => $_COOKIE['skautis_token'],
                'skautIS_IDRole' => '',
                'skautIS_IDUnit' => '',
                'skautIS_DateLogout' => $dateLogout,
            ];
            $skautis->setLoginData($reconstructedPost);
            global $_TEST_OVERRIDE;
            if (isset($_TEST_OVERRIDE) || $skautis->getUser()->isLoggedIn($hardCheck)) {
                try {
                    return $callback($skautis);
                } catch (\Skautis\Exception $e) {
                    throw new SkautISException($e);
                }
            }
        }
        throw new AuthenticationException;
    }

    public static function roleTry(callable $callback, bool $hardCheck, Role $requiredRole)
    {
        $_API_SECRETS_EXEC = 1;
        $SECRETS = require $_SERVER['DOCUMENT_ROOT'].'/api-secrets.php';
        if (Role::compare($requiredRole, new Role('guest')) === 0) {
            return $callback(Skautis::getInstance($SECRETS->skautis_app_id, $SECRETS->skautis_test_mode));
        }
        if (Role::compare($requiredRole, new Role('user')) === 0) {
            return self::skautisTry($callback, $hardCheck);
        }
        $safeCallback = function (Skautis $skautis) use ($callback, $requiredRole) {
            global $_TEST_OVERRIDE;
            $role = isset($_TEST_OVERRIDE)
                ? new Role($_TEST_OVERRIDE)
                : Role::get($skautis->UserManagement->LoginDetail()->ID_Person);
            if (Role::compare($role, $requiredRole) >= 0) {
                return $callback($skautis);
            } else {
                throw new RoleException;
            }
        };

        return self::skautisTry($safeCallback, $hardCheck);
    }

    public static function checkLessonGroup(UuidInterface $lessonId, bool $overrideGroup = false): bool
    {
        require $_SERVER['DOCUMENT_ROOT'].'/api-config.php';
        // @phpstan-ignore-next-line
        require $CONFIG->basepath.'/v1.0/endpoints/accountEndpoint.php';

        $groupSQL = <<<'SQL'
SELECT `group_id` FROM `groups_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;

        // @phpstan-ignore-next-line
        $loginState = $accountEndpoint->call('GET', new Role('guest'), ['no-avatar' => 'true']);

        if ($loginState['status'] == '200') {
            if (
                $overrideGroup and
                in_array(
                    $loginState['response']['role'],
                    ['editor', 'administrator', 'superuser']
                )
            ) {
                return true;
            }
            $groups = $loginState['response']['groups'];
            $groups[] = '00000000-0000-0000-0000-000000000000';
        } else {
            $groups = ['00000000-0000-0000-0000-000000000000'];
        }
        array_walk($groups, '\Ramsey\Uuid\Uuid::fromString');

        $db = new Database;
        $db->prepare($groupSQL);
        $lessonId = $lessonId->getBytes();
        $db->bindParam(':lesson_id', $lessonId, \PDO::PARAM_STR);
        $db->execute();
        $groupId = '';
        $db->bindColumn('group_id', $groupId);
        while ($db->fetch()) {
            if (in_array(Uuid::fromBytes(strval($groupId)), $groups)) {
                return true;
            }
        }

        return false;
    }

    /** @SuppressWarnings("PHPMD.ExcessiveMethodLength") */
    public static function urlEscape(string $str): string
    {
        $lookupTable = [
            // phpcs:disable Generic.Files.LineLength.TooLong
            ['base' => 'a', 'letters' => '[\x{0061}\x{24D0}\x{FF41}\x{1E9A}\x{00E0}\x{00E1}\x{00E2}\x{1EA7}\x{1EA5}\x{1EAB}\x{1EA9}\x{00E3}\x{0101}\x{0103}\x{1EB1}\x{1EAF}\x{1EB5}\x{1EB3}\x{0227}\x{01E1}\x{00E4}\x{01DF}\x{1EA3}\x{00E5}\x{01FB}\x{01CE}\x{0201}\x{0203}\x{1EA1}\x{1EAD}\x{1EB7}\x{1E01}\x{0105}\x{2C65}\x{0250}]'],
            ['base' => 'aa', 'letters' => '[\x{A733}]'],
            ['base' => 'ae', 'letters' => '[\x{00E6}\x{01FD}\x{01E3}]'],
            ['base' => 'ao', 'letters' => '[\x{A735}]'],
            ['base' => 'au', 'letters' => '[\x{A737}]'],
            ['base' => 'av', 'letters' => '[\x{A739}\x{A73B}]'],
            ['base' => 'ay', 'letters' => '[\x{A73D}]'],
            ['base' => 'b', 'letters' => '[\x{0062}\x{24D1}\x{FF42}\x{1E03}\x{1E05}\x{1E07}\x{0180}\x{0183}\x{0253}]'],
            ['base' => 'c', 'letters' => '[\x{0063}\x{24D2}\x{FF43}\x{0107}\x{0109}\x{010B}\x{010D}\x{00E7}\x{1E09}\x{0188}\x{023C}\x{A73F}\x{2184}]'],
            ['base' => 'd', 'letters' => '[\x{0064}\x{24D3}\x{FF44}\x{1E0B}\x{010F}\x{1E0D}\x{1E11}\x{1E13}\x{1E0F}\x{0111}\x{018C}\x{0256}\x{0257}\x{A77A}]'],
            ['base' => 'dz', 'letters' => '[\x{01F3}\x{01C6}]'],
            ['base' => 'e', 'letters' => '[\x{0065}\x{24D4}\x{FF45}\x{00E8}\x{00E9}\x{00EA}\x{1EC1}\x{1EBF}\x{1EC5}\x{1EC3}\x{1EBD}\x{0113}\x{1E15}\x{1E17}\x{0115}\x{0117}\x{00EB}\x{1EBB}\x{011B}\x{0205}\x{0207}\x{1EB9}\x{1EC7}\x{0229}\x{1E1D}\x{0119}\x{1E19}\x{1E1B}\x{0247}\x{025B}\x{01DD}]'],
            ['base' => 'f', 'letters' => '[\x{0066}\x{24D5}\x{FF46}\x{1E1F}\x{0192}\x{A77C}]'],
            ['base' => 'g', 'letters' => '[\x{0067}\x{24D6}\x{FF47}\x{01F5}\x{011D}\x{1E21}\x{011F}\x{0121}\x{01E7}\x{0123}\x{01E5}\x{0260}\x{A7A1}\x{1D79}\x{A77F}]'],
            ['base' => 'h', 'letters' => '[\x{0068}\x{24D7}\x{FF48}\x{0125}\x{1E23}\x{1E27}\x{021F}\x{1E25}\x{1E29}\x{1E2B}\x{1E96}\x{0127}\x{2C68}\x{2C76}\x{0265}]'],
            ['base' => 'hv', 'letters' => '[\x{0195}]'],
            ['base' => 'i', 'letters' => '[\x{0069}\x{24D8}\x{FF49}\x{00EC}\x{00ED}\x{00EE}\x{0129}\x{012B}\x{012D}\x{00EF}\x{1E2F}\x{1EC9}\x{01D0}\x{0209}\x{020B}\x{1ECB}\x{012F}\x{1E2D}\x{0268}\x{0131}]'],
            ['base' => 'j', 'letters' => '[\x{006A}\x{24D9}\x{FF4A}\x{0135}\x{01F0}\x{0249}]'],
            ['base' => 'k', 'letters' => '[\x{006B}\x{24DA}\x{FF4B}\x{1E31}\x{01E9}\x{1E33}\x{0137}\x{1E35}\x{0199}\x{2C6A}\x{A741}\x{A743}\x{A745}\x{A7A3}]'],
            ['base' => 'l', 'letters' => '[\x{013E}]'],
            ['base' => 'lj', 'letters' => '[\x{01C9}]'],
            ['base' => 'm', 'letters' => '[\x{006D}\x{24DC}\x{FF4D}\x{1E3F}\x{1E41}\x{1E43}\x{0271}\x{026F}]'],
            ['base' => 'n', 'letters' => '[\x{006E}\x{24DD}\x{FF4E}\x{01F9}\x{0144}\x{00F1}\x{1E45}\x{0148}\x{1E47}\x{0146}\x{1E4B}\x{1E49}\x{019E}\x{0272}\x{0149}\x{A791}\x{A7A5}]'],
            ['base' => 'nj', 'letters' => '[\x{01CC}]'],
            ['base' => 'o', 'letters' => '[\x{006F}\x{24DE}\x{FF4F}\x{00F2}\x{00F3}\x{00F4}\x{1ED3}\x{1ED1}\x{1ED7}\x{1ED5}\x{00F5}\x{1E4D}\x{022D}\x{1E4F}\x{014D}\x{1E51}\x{1E53}\x{014F}\x{022F}\x{0231}\x{00F6}\x{022B}\x{1ECF}\x{0151}\x{01D2}\x{020D}\x{020F}\x{01A1}\x{1EDD}\x{1EDB}\x{1EE1}\x{1EDF}\x{1EE3}\x{1ECD}\x{1ED9}\x{01EB}\x{01ED}\x{00F8}\x{01FF}\x{0254}\x{A74B}\x{A74D}\x{0275}]'],
            ['base' => 'oe', 'letters' => '[\x{0153}]'],
            ['base' => 'oi', 'letters' => '[\x{01A3}]'],
            ['base' => 'ou', 'letters' => '[\x{0223}]'],
            ['base' => 'oo', 'letters' => '[\x{A74F}]'],
            ['base' => 'p', 'letters' => '[\x{0070}\x{24DF}\x{FF50}\x{1E55}\x{1E57}\x{01A5}\x{1D7D}\x{A751}\x{A753}\x{A755}]'],
            ['base' => 'q', 'letters' => '[\x{0071}\x{24E0}\x{FF51}\x{024B}\x{A757}\x{A759}]'],
            ['base' => 'r', 'letters' => '[\x{0072}\x{24E1}\x{FF52}\x{0155}\x{1E59}\x{0159}\x{0211}\x{0213}\x{1E5B}\x{1E5D}\x{0157}\x{1E5F}\x{024D}\x{027D}\x{A75B}\x{A7A7}\x{A783}]'],
            ['base' => 's', 'letters' => '[\x{0073}\x{24E2}\x{FF53}\x{00DF}\x{015B}\x{1E65}\x{015D}\x{1E61}\x{0161}\x{1E67}\x{1E63}\x{1E69}\x{0219}\x{015F}\x{023F}\x{A7A9}\x{A785}\x{1E9B}]'],
            ['base' => 't', 'letters' => '[\x{0074}\x{24E3}\x{FF54}\x{1E6B}\x{1E97}\x{0165}\x{1E6D}\x{021B}\x{0163}\x{1E71}\x{1E6F}\x{0167}\x{01AD}\x{0288}\x{2C66}\x{A787}]'],
            ['base' => 'tz', 'letters' => '[\x{A729}]'],
            ['base' => 'u', 'letters' => '[\x{0075}\x{24E4}\x{FF55}\x{00F9}\x{00FA}\x{00FB}\x{0169}\x{1E79}\x{016B}\x{1E7B}\x{016D}\x{00FC}\x{01DC}\x{01D8}\x{01D6}\x{01DA}\x{1EE7}\x{016F}\x{0171}\x{01D4}\x{0215}\x{0217}\x{01B0}\x{1EEB}\x{1EE9}\x{1EEF}\x{1EED}\x{1EF1}\x{1EE5}\x{1E73}\x{0173}\x{1E77}\x{1E75}\x{0289}]'],
            ['base' => 'v', 'letters' => '[\x{0076}\x{24E5}\x{FF56}\x{1E7D}\x{1E7F}\x{028B}\x{A75F}\x{028C}]'],
            ['base' => 'vy', 'letters' => '[\x{A761}]'],
            ['base' => 'w', 'letters' => '[\x{0077}\x{24E6}\x{FF57}\x{1E81}\x{1E83}\x{0175}\x{1E87}\x{1E85}\x{1E98}\x{1E89}\x{2C73}]'],
            ['base' => 'x', 'letters' => '[\x{0078}\x{24E7}\x{FF58}\x{1E8B}\x{1E8D}]'],
            ['base' => 'y', 'letters' => '[\x{0079}\x{24E8}\x{FF59}\x{1EF3}\x{00FD}\x{0177}\x{1EF9}\x{0233}\x{1E8F}\x{00FF}\x{1EF7}\x{1E99}\x{1EF5}\x{01B4}\x{024F}\x{1EFF}]'],
            ['base' => 'z', 'letters' => '[\x{007A}\x{24E9}\x{FF5A}\x{017A}\x{1E91}\x{017C}\x{017E}\x{1E93}\x{1E95}\x{01B6}\x{0225}\x{0240}\x{2C6C}\x{A763}]'],
            // phpcs:enable Generic.Files.LineLength.TooLong
        ];

        $str = mb_strtolower(trim($str));
        foreach ($lookupTable as $pair) {
            $str = mb_ereg_replace($pair['letters'], $pair['base'], strval($str));
        }
        $str = mb_ereg_replace('\s+', '-', strval($str));
        $str = mb_ereg_replace('[^\w\-]+', '', strval($str));
        $str = mb_ereg_replace('_', '-', strval($str));
        $str = mb_ereg_replace('\-\-+', '-', strval($str));

        return strval($str);
    }
}
