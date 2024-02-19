<?php

declare(strict_types=1);

namespace Skaut\OdyMarkdown\v1_0;

use cebe\markdown\GithubMarkdown;

class OdyMarkdown extends GithubMarkdown
{
    private static function trimUrl(string $url): string
    {
        $url = trim($url);
        if (mb_substr($url, mb_strlen($url) - 1) === "/") {
            $url = mb_substr($url, 0, mb_strlen($url) - 1);
        }
        $url = mb_ereg_replace("(^https?://)", "", $url);
        return $url;
    }

    // Link rendering as text with address in parentheses
    protected function renderLink($block): string
    {
        if (isset($block['refkey'])) {
            if (($ref = $this->lookupReference($block['refkey'])) !== false) {
                $block = array_merge($block, $ref);
            } else {
                return $block['orig'];
            }
        }
        $text = $this->renderAbsy($block['text']);
        $trimmedText = self::trimUrl($this->renderAbsy($block['text']));
        $trimmedUrl = self::trimUrl($block['url']);
        if ($trimmedText !== $trimmedUrl) {
            $text .= ' (' . $block['url'] . ') ';
        }
        return $text;
    }

    // Image rendering in original quality
    protected function renderImage($block): string
    {
        global $CONFIG;
        if (isset($block['refkey'])) {
            if (($ref = $this->lookupReference($block['refkey'])) !== false) {
                $block = array_merge($block, $ref);
            } else {
                return $block['orig'];
            }
        }

        if (mb_strpos($block['url'], $CONFIG->apiuri . '/v1.0/image') !== false) {
            if (mb_strpos($block['url'], 'quality=') !== false) {
                $block['url'] = str_replace('quality=web', 'quality=original', $block['url']);
                $block['url'] = str_replace('quality=thumbnail', 'quality=original', $block['url']);
            } else {
                $block['url'] .= '?quality=original';
            }
        }

        return parent::renderImage($block);
    }

    // Generic functions for command parsing
    private function identifyCommand(string $line, string $command): bool
    {
        if (strncmp(trim($line), '!' . $command, mb_strlen($command) + 1) === 0) {
            return true;
        }
        return false;
    }

    private function consumeCommand(array $lines, int $current, string $command): array
    {
        $block = [0 => $command, 'lastPage' => ($current + 1 == count($lines))];
        [$argumentString, $next] = self::getArgumentString($lines, $current, $command);
        $argumentArray = explode(',', strval($argumentString));
        foreach ($argumentArray as $arg) {
            if ($arg === '') {
                continue;
            }
            $keyval = explode('=', $arg);
            if (count($keyval) === 1) {
                $block[$keyval[0]] = true;
            } elseif (count($keyval) === 2) {
                $block[$keyval[0]] = $keyval[1];
            } else {
                break;
            }
        }
        return [$block, $next];
    }


    private static function getArgumentString(array $lines, int $current, string $command): array
    {
        $line = rtrim($lines[$current]);
        $next = $current;
        $argumentString = '';
        $start = mb_strpos($line, '[', mb_strlen($command) + 1);
        if ($start !== false) {
            $stop = mb_strpos($line, ']', $start + 1);
            if ($stop !== false) {
                $argumentString = mb_substr($line, $start + 1, $stop - $start - 1);
                $next = $current;
            } else {
                $argumentString = mb_substr($line, $start + 1);
                $linecount = count($lines);
                for ($i = $current + 1; $i < $linecount; ++$i) {
                    $stop = mb_strpos($lines[$i], "]");
                    if ($stop !== false) {
                        $argumentString .= mb_substr($lines[$i], 0, $stop);
                        $next = $i;
                        break;
                    } else {
                        $argumentString .= $lines[$i];
                    }
                }
            }
        }
        $argumentString = mb_ereg_replace(' ', '', strval($argumentString));
        return [$argumentString, $next];
    }

    // Notes extension
    protected function identifyNotes(string $line): bool
    {
        return $this->identifyCommand($line, 'linky');
    }

    protected function consumeNotes(array $lines, int $current): array
    {
        return $this->consumeCommand($lines, $current, 'linky');
    }

    protected function renderLinky(array $block): string
    {
        $dotted = (isset($block['teckovane']) and $block['teckovane'] === true);
        $height = 1;
        if (isset($block['pocet'])) {
            if ($block['pocet'] === 'strana') {
                $pgbr = $block['lastPage'] ? '' : '<pagebreak>';
                if ($dotted) {
                    return '<br><div class="dottedpage">' . str_repeat('<br><div class="dottedline">' . str_repeat('.', 256) . '</div>', 32) . '</div>' . $pgbr;
                }
                return $pgbr;
            } else {
                $height = intval($block['pocet']);
            }
        }
        if ($dotted) {
            return str_repeat('<br><div class="dottedline">' . str_repeat('.', 256) . '</div>', $height);
        } else {
            return str_repeat('<br>', $height);
        }
    }

    // Pagebreak extension
    protected function identifyPagebreak(string $line): bool
    {
        return $this->identifyCommand($line, 'novastrana');
    }

    protected function consumePagebreak(array $lines, int $current): array
    {
        return $this->consumeCommand($lines, $current, 'novastrana');
    }

    protected function renderNovastrana(array $block): string
    {
        return $block['lastPage'] ? '' : '<pagebreak>';
    }
}
