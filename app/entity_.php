<?php


class ads_buy
{
    public static function parse(string $text, array $entities): string
    {
        $ar = self::unParseEntities(self::encode($text), $entities);
        return self::join($ar);
    }

    private static function unParseEntities($text, array $entities, int $offset = 0, int $length = null): Generator
    {
        $length = $length ?? strlen($text);

        foreach ($entities as $index => $entity) {
            if ($entity['offset'] * 2 < $offset) {
                continue;
            }

            if ($entity['offset'] * 2 > $offset) {
                yield self::quote(self::decode(substr($text, $offset, $entity['offset'] * 2 - $offset)));
            }

            $start = $entity['offset'] * 2;
            $offset = $entity['offset'] * 2+ $entity['length'] * 2;
            //$sub_entities = array_filter(array_slice($entities, $index+1), fn($e) => $e['offset']*2 < $offset);

            $e = function () { 
                $e['offset']*2 < $offset; 
            };

            $sub_entities = array_filter(array_slice($entities, $index+1), $e);
            yield self::applyEntity(self::join(self::unParseEntities($text, $sub_entities, $start, $offset)), $entity);
        }

        if ($offset < $length) {
            yield self::quote(self::decode(substr($text, $offset, $length - $offset)));
        }
    }

    private static function applyEntity($text, array $entity)
    {
        if (in_array($entity['type'], ['bot_command', 'url', 'mention', 'phone_number'])) {
            return $text;
        }

        if (in_array($entity['type'], ['bold', 'italic', 'code', 'underline', 'strikethrough', 'pre'])) {
            $format = [
                'bold' => '**%s**',
                'italic' => '__%s__',
                'code' => '`%s`',
                'underline' => '~~%s~~',
                'strikethrough' => '~~%s~~',
                'pre' => '```%s```'
            ];
            return sprintf($format[$entity['type']], $text);
        }

        if ($entity['type'] === 'text_mention') {
            return sprintf('[%s](tg://user?id=%s)', $text, $entity['user']['id']);
        }

        if ($entity['type'] === 'text_link') {
            return sprintf('[%s](%s)', $text, $entity['url']);
        }

        return self::quote($text);
    }

    private static function encode(string $text): string
    {
        return iconv('UTF-8', 'UTF-16LE', $text);
    }

    private static function decode(string $text): string
    {
        return iconv('UTF-16LE', 'UTF-8', $text);
    }

    private static function quote($text): QuotedString
    {
        if ($text instanceof QuotedString) {
            return $text;
        }
        return new QuotedString(str_replace(['&', '<', '>'], ['&amp;', '&lt;', '&gt;'], $text));
    }

    private static function join(Iterator $iterator): QuotedString
    {
        $array = iterator_to_array($iterator);
        return new QuotedString(implode($array));
    }
}

class QuotedString
{
    //private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}