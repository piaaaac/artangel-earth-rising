<?php

namespace Kirby\Toolkit;

use Closure;
use DateTime;
use Exception;
use IntlDateFormatter;
use Kirby\Cms\App;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Query\Query;
use Throwable;

/**
 * The String class provides a set
 * of handy methods for string
 * handling and manipulation.
 *
 * @package   Kirby Toolkit
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class Str
{
	/**
	 * Language translation table
	 */
	public static array $language = [];

	/**
	 * Ascii translation table
	 */
	public static array $ascii = [
		'/°|₀/' => '0',
		'/¹|₁/' => '1',
		'/²|₂/' => '2',
		'/³|₃/' => '3',
		'/⁴|₄/' => '4',
		'/⁵|₅/' => '5',
		'/⁶|₆/' => '6',
		'/⁷|₇/' => '7',
		'/⁸|₈/' => '8',
		'/⁹|₉/' => '9',
		'/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ|Ä|A/' => 'A',
		'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|æ|ǽ|ä|a|а/' => 'a',
		'/Б/' => 'B',
		'/б/' => 'b',
		'/Ç|Ć|Ĉ|Ċ|Č|Ц/' => 'C',
		'/ç|ć|ĉ|ċ|č|ц/' => 'c',
		'/Ð|Ď|Đ/' => 'Dj',
		'/ð|ď|đ/' => 'dj',
		'/Д/' => 'D',
		'/д/' => 'd',
		'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Е|Ё|Э/' => 'E',
		'/è|é|ê|ë|ē|ĕ|ė|ę|ě|е|ё|э/' => 'e',
		'/Ф/' => 'F',
		'/ƒ|ф/' => 'f',
		'/Ĝ|Ğ|Ġ|Ģ|Г/' => 'G',
		'/ĝ|ğ|ġ|ģ|г/' => 'g',
		'/Ĥ|Ħ|Х/' => 'H',
		'/ĥ|ħ|х/' => 'h',
		'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|И/' => 'I',
		'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|и|i̇/' => 'i',
		'/Ĵ|Й/' => 'J',
		'/ĵ|й/' => 'j',
		'/Ķ|К/' => 'K',
		'/ķ|к/' => 'k',
		'/Ĺ|Ļ|Ľ|Ŀ|Ł|Л/' => 'L',
		'/ĺ|ļ|ľ|ŀ|ł|л/' => 'l',
		'/М/' => 'M',
		'/м/' => 'm',
		'/Ñ|Ń|Ņ|Ň|Н/' => 'N',
		'/ñ|ń|ņ|ň|ŉ|н/' => 'n',
		'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ö|O/' => 'O',
		'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ö|o|о/' => 'o',
		'/П/' => 'P',
		'/п/' => 'p',
		'/Ŕ|Ŗ|Ř|Р/' => 'R',
		'/ŕ|ŗ|ř|р/' => 'r',
		'/Ś|Ŝ|Ş|Ș|Š|С/' => 'S',
		'/ś|ŝ|ş|ș|š|ſ|с/' => 's',
		'/Ţ|Ț|Ť|Ŧ|Т/' => 'T',
		'/ţ|ț|ť|ŧ|т/' => 't',
		'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|У|Ü|U/' => 'U',
		'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|у|ü|u/' => 'u',
		'/В/' => 'V',
		'/в/' => 'v',
		'/Ý|Ÿ|Ŷ|Ы/' => 'Y',
		'/ý|ÿ|ŷ|ы/' => 'y',
		'/Ŵ/' => 'W',
		'/ŵ/' => 'w',
		'/Ź|Ż|Ž|З/' => 'Z',
		'/ź|ż|ž|з/' => 'z',
		'/Æ|Ǽ/' => 'AE',
		'/ß/' => 'ss',
		'/Ĳ/' => 'IJ',
		'/ĳ/' => 'ij',
		'/Œ/' => 'OE',
		'/Ч/' => 'Ch',
		'/ч/' => 'ch',
		'/Ю/' => 'Ju',
		'/ю/' => 'ju',
		'/Я/' => 'Ja',
		'/я/' => 'ja',
		'/Ш/' => 'Sh',
		'/ш/' => 'sh',
		'/Щ/' => 'Shch',
		'/щ/' => 'shch',
		'/Ж/' => 'Zh',
		'/ж/' => 'zh',
	];

	/**
	 * Default settings for class methods
	 */
	public static array $defaults = [
		'slug' => [
			'separator' => '-',
			'allowed'   => 'a-z0-9'
		]
	];

	/**
	 * Parse accepted values and their quality from an
	 * accept string like an Accept or Accept-Language header
	 */
	public static function accepted(string $input): array
	{
		$items = [];

		// check each type in the Accept header
		foreach (static::split($input, ',') as $item) {
			$parts   = static::split($item, ';');
			$value   = A::first($parts); // $parts now only contains params
			$quality = 1;

			// check for the q param ("quality" of the type)
			foreach ($parts as $param) {
				$param = static::split($param, '=');

				if (A::get($param, 0) === 'q' && empty($param[1]) === false) {
					$quality = $param[1];
				}
			}

			$items[$quality][] = $value;
		}

		// sort items by quality
		krsort($items);

		$result = [];

		foreach ($items as $quality => $values) {
			foreach ($values as $value) {
				$result[] = [
					'quality' => (float)$quality,
					'value'   => $value
				];
			}
		}

		return $result;
	}

	/**
	 * Returns the rest of the string after the given substring or character
	 */
	public static function after(
		string $string,
		string $needle,
		bool $caseInsensitive = false
	): string {
		$position = static::position($string, $needle, $caseInsensitive);

		if ($position === false) {
			return '';
		}

		return static::substr($string, $position + static::length($needle));
	}

	/**
	 * Removes the given substring or character
	 * only from the start of the string
	 * @since 3.7.0
	 */
	public static function afterStart(
		string $string,
		string $needle,
		bool $caseInsensitive = false
	): string {
		if ($needle === '') {
			return $string;
		}

		if (static::startsWith($string, $needle, $caseInsensitive) === true) {
			return static::substr($string, static::length($needle));
		}

		return $string;
	}

	/**
	 * Convert a string to 7-bit ASCII.
	 */
	public static function ascii(string $string): string
	{
		$string  = str_replace(
			array_keys(static::$language),
			array_values(static::$language),
			$string
		);

		$string  = preg_replace(
			array_keys(static::$ascii),
			array_values(static::$ascii),
			$string
		);

		return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $string);
	}

	/**
	 * Returns the beginning of a string before the given substring or character
	 */
	public static function before(
		string $string,
		string $needle,
		bool $caseInsensitive = false
	): string {
		$position = static::position($string, $needle, $caseInsensitive);

		if ($position === false) {
			return '';
		}

		return static::substr($string, 0, $position);
	}

	/**
	 * Removes the given substring or character only from the end of the string
	 * @since 3.7.0
	 */
	public static function beforeEnd(
		string $string,
		string $needle,
		bool $caseInsensitive = false
	): string {
		if ($needle === '') {
			return $string;
		}

		if (static::endsWith($string, $needle, $caseInsensitive) === true) {
			return static::substr($string, 0, -static::length($needle));
		}

		return $string;
	}

	/**
	 * Returns everything between two strings from the first occurrence of a given string
	 */
	public static function between(
		string|null $string,
		string $start,
		string $end
	): string {
		return static::before(static::after($string, $start), $end);
	}

	/**
	 * Converts a string to camel case
	 *
	 * @param string $value The string to convert
	 */
	public static function camel(string|null $value): string
	{
		return lcfirst(static::studly($value));
	}

	/**
	 * Converts a camel-case string to kebab-case
	 * @since 4.0.0
	 *
	 * @param string $value The string to convert
	 */
	public static function camelToKebab(string|null $value): string
	{
		$value = preg_replace('!([a-z0-9])([A-Z])!', '$1-$2', $value);
		return static::lower($value);
	}

	/**
	 * Checks if a str contains another string
	 */
	public static function contains(
		string|null $string,
		string $needle,
		bool $caseInsensitive = false
	): bool {
		if ($needle === '') {
			return true;
		}

		$method = match ($caseInsensitive) {
			true  => 'stripos',
			false => 'strpos'
		};

		return call_user_func($method, $string ?? '', $needle) !== false;
	}

	/**
	 * Convert timestamp to date string
	 * according to locale settings
	 *
	 * @param 'date'|'intl'|'strftime'|null $handler Custom date handler or `null`
	 *                                               for the globally configured one
	 */
	public static function date(
		int|null $time,
		string|IntlDateFormatter|null $format = null,
		string|null $handler = null
	): string|int|false {
		if (is_null($format) === true) {
			return $time;
		}

		// $format is an IntlDateFormatter instance
		if ($format instanceof IntlDateFormatter) {
			return $format->format($time ?? time());
		}

		// automatically determine the handler from global configuration
		// if an app instance is already running; otherwise fall back to
		// `date` for backwards-compatibility
		if ($handler === null) {
			$handler = App::instance(null, true)?->option('date.handler') ?? 'date';
		}

		// `intl` handler
		if ($handler === 'intl') {
			$datetime = new DateTime();

			if ($time !== null) {
				$datetime->setTimestamp($time);
			}

			return IntlDateFormatter::formatObject($datetime, $format);
		}

		// handle `strftime` to be able
		// to suppress deprecation warning
		// TODO: remove strftime support for PHP 9.0
		if ($handler === 'strftime') {
			// make sure timezone is set correctly
			date_default_timezone_get();

			return @strftime($format, $time);
		}

		return $handler($format, $time);
	}

	/**
	 * Converts a string to a different encoding
	 */
	public static function convert(
		string $string,
		string $targetEncoding,
		string|null $sourceEncoding = null
	): string {
		// detect the source encoding if not passed as third argument
		$sourceEncoding ??= static::encoding($string);

		// no need to convert if the target encoding is the same
		if (strtolower($sourceEncoding) === strtolower($targetEncoding)) {
			return $string;
		}

		return iconv($sourceEncoding, $targetEncoding, $string);
	}

	/**
	 * Encode a string (used for email addresses)
	 */
	public static function encode(string $string): string
	{
		$encoded = '';

		for ($i = 0; $i < static::length($string); $i++) {
			$char     = static::substr($string, $i, 1);
			$char     = mb_convert_encoding($char, 'UCS-4BE', 'UTF-8');
			[, $code] = unpack('N', $char);
			$encoded .= match (random_int(1, 2)) {
				1 => '&#' . $code . ';',
				2 => '&#x' . dechex($code) . ';'
			};
		}

		return $encoded;
	}

	/**
	 * Tries to detect the string encoding
	 */
	public static function encoding(string $string): string
	{
		return mb_detect_encoding(
			$string,
			'UTF-8, ISO-8859-1, windows-1251',
			true
		);
	}

	/**
	 * Checks if a string ends with the passed needle
	 */
	public static function endsWith(
		string|null $string,
		string $needle,
		bool $caseInsensitive = false
	): bool {
		if ($needle === '') {
			return true;
		}

		$probe = static::substr($string, -static::length($needle));

		if ($caseInsensitive === true) {
			$needle = static::lower($needle);
			$probe  = static::lower($probe);
		}

		return $needle === $probe;
	}

	/**
	 * Escape string for context specific output
	 * @since 3.7.0
	 *
	 * @param string $string Untrusted data
	 * @param string $context Location of output (`html`, `attr`, `js`, `css`, `url` or `xml`)
	 * @return string Escaped data
	 */
	public static function esc(
		string $string,
		string $context = 'html'
	): string {
		if (method_exists(Escape::class, $context) === true) {
			return Escape::$context($string);
		}

		return $string;
	}

	/**
	 * Creates an excerpt of a string
	 * It removes all html tags first and then cuts the string
	 * according to the specified number of chars.
	 *
	 * @param string $string The string to be shortened
	 * @param int $chars The final number of characters the string should have
	 * @param bool $strip True: remove the HTML tags from the string first
	 * @param string $rep The element, which should be added if the string is too long. Ellipsis is the default.
	 * @return string The shortened string
	 */
	public static function excerpt(
		string $string,
		int $chars = 140,
		bool $strip = true,
		string $rep = ' …'
	): string {
		if ($strip === true) {
			// ensure that opening tags are preceded by a space, so that
			// when tags are skipped we can be sure that words stay separate
			$string = preg_replace('#\s*<([^\/])#', ' <${1}', $string);

			// in strip mode, we always return plain text
			$string = strip_tags($string);
		}

		// replace line breaks with spaces
		$string = str_replace(PHP_EOL, ' ', trim($string));

		// remove double spaces
		$string = preg_replace('![ ]{2,}!', ' ', $string);

		if ($chars === 0) {
			return $string;
		}

		if (static::length($string) <= $chars) {
			return $string;
		}

		// shorten the string to the specified number of characters,
		// but make sure to not cut off in the middle of a word
		$excerpt = static::substr($string, 0, $chars);
		$cutoff  = mb_strrpos($excerpt, ' ');

		if ($cutoff !== false) {
			$excerpt = static::substr($string, 0, $cutoff);
		}

		return $excerpt . $rep;
	}

	/**
	 * Convert the value to a float with a decimal
	 * point, no matter what the locale setting is
	 */
	public static function float(string|int|float|null $value): string
	{
		// make sure $value is not null
		$value ??= '';

		// turn the value into a string
		$value = (string)$value;

		// Convert exponential to decimal, 1e-8 as 0.00000001
		if (str_contains(strtolower($value), 'e') === true) {
			$value = rtrim(sprintf('%.16f', (float)$value), '0');
		}

		$value   = str_replace(',', '.', $value);
		$decimal = strrchr($value, '.');
		$decimal = match ($decimal) {
			false   => 0,
			default => strlen($decimal) - 1
		};

		return number_format((float)$value, $decimal, '.', '');
	}

	/**
	 * Returns the rest of the string starting from the given character
	 */
	public static function from(
		string $string,
		string $needle,
		bool $caseInsensitive = false
	): string {
		$position = static::position($string, $needle, $caseInsensitive);

		if ($position === false) {
			return '';
		}

		return static::substr($string, $position);
	}

	/**
	 * Adds `-1` to a string or increments the ending number to allow `-2`, `-3`, etc.
	 * @since 3.7.0
	 *
	 * @param string $string The string to increment
	 * @param int $first Starting number
	 */
	public static function increment(
		string $string,
		string $separator = '-',
		int $first = 1
	): string {
		preg_match('/(.+)' . preg_quote($separator, '/') . '([0-9]+)$/', $string, $matches);

		if (isset($matches[2]) === true) {
			// increment the existing ending number
			return $matches[1] . $separator . ((int)$matches[2] + 1);
		}

		// append a new ending number
		return $string . $separator . $first;
	}

	/**
	 * Convert a string to kebab case.
	 */
	public static function kebab(string|null $value): string
	{
		return static::snake($value, '-');
	}

	/**
	 * Convert a kebab case string to camel case.
	 */
	public static function kebabToCamel(string|null $value): string
	{
		return ucfirst(preg_replace_callback(
			'/-(.)/',
			fn ($matches) => strtoupper($matches[1]),
			$value ?? ''
		));
	}

	/**
	 * A UTF-8 safe version of strlen()
	 */
	public static function length(string|null $string): int
	{
		return mb_strlen($string ?? '', 'UTF-8');
	}

	/**
	 * A UTF-8 safe version of strtolower()
	 */
	public static function lower(string|null $string): string
	{
		return mb_strtolower($string ?? '', 'UTF-8');
	}

	/**
	 * Safe ltrim alternative
	 */
	public static function ltrim(string $string, string $trim = ' '): string
	{
		return preg_replace('!^(' . preg_quote($trim) . ')+!', '', $string);
	}

	/**
	 * Match string against a regular expression and return matches
	 *
	 * @param string $string The string to match
	 * @param string $pattern The regular expression
	 * @param int $flags Optional flags for PHP `preg_match()`
	 * @param int $offset Positional offset in the string to start the search
	 * @return array|null The matches or null if no match was found
	 */
	public static function match(
		string $string,
		string $pattern,
		int $flags = 0,
		int $offset = 0
	): array|null {
		$result = preg_match($pattern, $string, $matches, $flags, $offset);
		return match ($result) {
			1       => $matches,
			default => null
		};
	}

	/**
	 * Check whether a string matches a regular expression
	 *
	 * @param string $string The string to match
	 * @param string $pattern The regular expression
	 * @param int $flags Optional flags for PHP `preg_match()`
	 * @param int $offset Positional offset in the string to start the search
	 * @return bool True if the string matches the pattern
	 */
	public static function matches(
		string $string,
		string $pattern,
		int $flags = 0,
		int $offset = 0
	): bool {
		return static::match($string, $pattern, $flags, $offset) !== null;
	}

	/**
	 * Match string against a regular expression and return all matches
	 *
	 * @param string $string The string to match
	 * @param string $pattern The regular expression
	 * @param int $flags Optional flags for PHP `preg_match_all()`
	 * @param int $offset Positional offset in the string to start the search
	 * @return array|null The matches or null if no match was found
	 */
	public static function matchAll(
		string $string,
		string $pattern,
		int $flags = 0,
		int $offset = 0
	): array|null {
		$result = preg_match_all($pattern, $string, $matches, $flags, $offset);
		return match ($result > 0) {
			true  => $matches,
			false => null
		};
	}

	/**
	 * Get a character pool with various possible combinations
	 */
	public static function pool(
		string|array $type,
		bool $array = true
	): string|array {
		if (is_array($type) === true) {
			$pool = [];

			foreach ($type as $t) {
				$pool = [...$pool, ...static::pool($t)];
			}
		} else {
			$pool = match (strtolower($type)) {
				'alphalower' => range('a', 'z'),
				'alphaupper' => range('A', 'Z'),
				'alpha'      => static::pool(['alphaLower', 'alphaUpper']),
				'num'        => range(0, 9),
				'alphanum'   => static::pool(['alpha', 'num']),
				'base32'     => [...static::pool('alphaUpper'), ...range(2, 7)],
				'base32hex'  => [...range(0, 9), ...range('A', 'V')],
				default      => []
			};
		}

		return $array ? $pool : implode('', $pool);
	}

	/**
	 * Returns the position of a needle in a string
	 * if it can be found
	 *
	 * @throws \Kirby\Exception\InvalidArgumentException for empty $needle
	 */
	public static function position(
		string|null $string,
		string $needle,
		bool $caseInsensitive = false
	): int|false {
		if ($needle === '') {
			throw new InvalidArgumentException(
				message: 'The needle must not be empty'
			);
		}

		if ($caseInsensitive === true) {
			$string = static::lower($string);
			$needle = static::lower($needle);
		}

		return mb_strpos($string ?? '', $needle, 0, 'UTF-8');
	}

	/**
	 * Runs a string query.
	 * Check out the Query class for more information.
	 */
	public static function query(string $query, array $data = [])
	{
		return Query::factory($query)->resolve($data);
	}

	/**
	 * Generates a random string that may be used for cryptographic purposes
	 *
	 * @param int $length The length of the random string
	 * @param string $type Pool type (type of allowed characters)
	 */
	public static function random(
		int|null $length = null,
		string $type = 'alphaNum'
	): string|false {
		$length ??= random_int(5, 10);
		$pool     = static::pool($type, false);

		// catch invalid pools
		if (!$pool) {
			return false;
		}

		// regex that matches all characters
		// *not* in the pool of allowed characters
		$regex = '/[^' . $pool . ']/';

		// collect characters until we have our required length
		$result = '';

		while (($currentLength = strlen($result)) < $length) {
			$missing = $length - $currentLength;
			$bytes   = random_bytes($missing);
			$allowed = preg_replace($regex, '', base64_encode($bytes));
			$result .= substr($allowed, 0, $missing);
		}

		return $result;
	}

	/**
	 * Replaces all or some occurrences of the search string with the replacement string
	 * Extension of the str_replace() function in PHP with an additional $limit parameter
	 *
	 * @param string|array|Collection $string String being replaced on (haystack); can be an array of multiple subject strings
	 * @param string|array|Collection $search Value being searched for (needle)
	 * @param string|array|Collection $replace Value to replace matches with
	 * @param int|array $limit Maximum possible replacements for each search value;
	 *                         multiple limits for each search value are supported;
	 *                         defaults to no limit
	 * @return string|array String with replaced values;
	 *                      if $string is an array, array of strings
	 * @psalm-return ($string is array ? array : string)
	 */
	public static function replace(
		string|array|Collection $string,
		string|array|Collection $search,
		string|array|Collection $replace,
		int|array $limit = -1
	): string|array {
		// convert Kirby collections to arrays
		if ($string instanceof Collection) {
			$string = $string->toArray();
		}

		if ($search instanceof Collection) {
			$search  = $search->toArray();
		}

		if ($replace instanceof Collection) {
			$replace = $replace->toArray();
		}

		// without a limit we might as well use the built-in function
		if ($limit === -1) {
			return str_replace($search, $replace, $string ?? '');
		}

		// if the limit is zero, the result will be no replacements at all
		if ($limit === 0) {
			return $string;
		}

		// multiple subjects are run separately through this method
		if (is_array($string) === true) {
			$result = [];

			foreach ($string as $s) {
				$result[] = static::replace($s, $search, $replace, $limit);
			}

			return $result;
		}

		// build an array of replacements
		// we don't use an associative array because otherwise you couldn't
		// replace the same string with different replacements
		$replacements = static::replacements($search, $replace, $limit);

		// run the string and the replacement array through the replacer
		return static::replaceReplacements($string, $replacements);
	}

	/**
	 * Generates a replacement array out of dynamic input data
	 * Used for Str::replace()
	 *
	 * @param string|array $search Value being searched for (needle)
	 * @param string|array $replace Value to replace matches with
	 * @param int|array $limit Maximum possible replacements for each search value;
	 *                         multiple limits for each search value are supported;
	 *                         defaults to no limit
	 * @return array List of replacement arrays, each with a
	 *               'search', 'replace' and 'limit' attribute
	 */
	public static function replacements(
		string|array $search,
		string|array $replace,
		int|array $limit
	): array {
		if (is_array($search) === true) {
			$replacements = [];

			foreach ($search as $i => $s) {
				if (is_array($replace) === true) {
					// replace with an empty string if
					// no replacement string was defined for this index;
					// behavior is identical to official PHP str_replace()
					$r = $replace[$i] ?? '';
				}

				if (is_array($limit) === true) {
					// don't apply a limit if no limit
					// was defined for this index
					$l = $limit[$i] ?? -1;
				}

				$replacements[] = [
					'search'  => $s,
					'replace' => $r ?? $replace,
					'limit'   => $l ?? $limit
				];
			}

			return $replacements;
		}

		if (is_string($replace) === true && is_int($limit) === true) {
			return [compact('search', 'replace', 'limit')];
		}

		throw new InvalidArgumentException(
			message: 'Invalid combination of $search, $replace and $limit params.'
		);
	}

	/**
	 * Takes a replacement array and processes the replacements
	 * Used for Str::replace()
	 *
	 * @param string $string String being replaced on (haystack)
	 * @param array $replacements Replacement array from Str::replacements()
	 * @return string String with replaced values
	 */
	public static function replaceReplacements(
		string $string,
		array $replacements
	): string {
		// replace in the order of the replacements
		// behavior is identical to the official PHP str_replace()
		foreach ($replacements as $replacement) {
			if (is_int($replacement['limit']) === false) {
				throw new Exception(
					message: 'Invalid limit "' . $replacement['limit'] . '".'
				);
			}

			if ($replacement['limit'] === -1) {
				// no limit, we don't need our special replacement routine
				$string = str_replace(
					$replacement['search'],
					$replacement['replace'],
					$string
				);
				continue;
			}

			if ($replacement['limit'] > 0) {
				// limit given, only replace for as many times per replacement
				$position = -1;

				for ($i = 0; $i < $replacement['limit']; $i++) {
					$position = strpos(
						$string,
						$replacement['search'],
						$position + 1
					);

					if (is_int($position) === true) {
						$string = substr_replace(
							$string,
							$replacement['replace'],
							$position,
							strlen($replacement['search'])
						);
						// adapt $pos to the now changed offset
						$position = $position + strlen($replacement['replace']) - strlen($replacement['search']);
					} else {
						// no more match in the string
						break;
					}
				}
			}
		}

		return $string;
	}

	/**
	 * Safe rtrim alternative
	 */
	public static function rtrim(string $string, string $trim = ' '): string
	{
		return preg_replace('!(' . preg_quote($trim) . ')+$!', '', $string);
	}

	/**
	 * Replaces placeholders in string with values from the data array
	 * and escapes HTML in the results in `{{ }}` placeholders
	 * while leaving HTML special characters untouched in `{< >}` placeholders
	 *
	 * @since 3.6.0
	 *
	 * @param string|null $string The string with placeholders
	 * @param array $data Associative array with placeholders as
	 *                    keys and replacements as values.
	 *                    Supports query syntax.
	 * @param array $options An options array that contains:
	 *                       - fallback: if a token does not have any matches
	 *                       - callback: to be able to handle each matching result (escaping is applied after the callback)
	 *
	 * @return string The filled-in and partially escaped string
	 */
	public static function safeTemplate(
		string|null $string,
		array $data = [],
		array $options = []
	): string {
		$fallback = $options['fallback'] ?? null;
		$callback = $options['callback'] ?? null;

		if ($callback instanceof Closure === false) {
			$callback = null;
		}

		// replace and escape
		$string = static::template($string, $data, [
			'start'    => '{{',
			'end'      => '}}',
			'callback' => function ($result, $query, $data) use ($callback) {
				if ($callback !== null) {
					$result = $callback($result, $query, $data);
				}

				return Escape::html($result);
			},
			'fallback' => $fallback
		]);

		// replace unescaped (specifically marked placeholders)
		$string = static::template($string, $data, [
			'start'    => '{<',
			'end'      => '>}',
			'callback' => $callback,
			'fallback' => $fallback
		]);

		return $string;
	}

	/**
	 * Shortens a string and adds an ellipsis if the string is too long
	 *
	 * ```php
	 * echo Str::short('This is a very, very, very long string', 10);
	 * // output: This is a…
	 *
	 * echo Str::short('This is a very, very, very long string', 10, '####');
	 * // output: This i####
	 * ```
	 *
	 * @param string $string The string to be shortened
	 * @param int $length The final number of characters the
	 *                    string should have
	 * @param string $appendix The element, which should be added if the
	 *                         string is too long. Ellipsis is the default.
	 * @return string The shortened string
	 */
	public static function short(
		string|null $string,
		int $length = 0,
		string $appendix = '…'
	): string {
		if ($string === null) {
			return '';
		}

		if ($length === 0) {
			return $string;
		}

		if (static::length($string) <= $length) {
			return $string;
		}

		return static::substr($string, 0, $length) . $appendix;
	}

	/**
	 * Calculates the similarity between two strings with multibyte support
	 * @since 3.5.2
	 *
	 * @author Based on the work of Antal Áron
	 * @copyright Original Copyright (c) 2017, Antal Áron
	 * @license https://github.com/antalaron/mb-similar-text/blob/master/LICENSE MIT License
	 *
	 * @param bool $caseInsensitive If `true`, strings are compared case-insensitively
	 * @return array matches: Number of matching chars in both strings
	 *               percent: Similarity in percent
	 */
	public static function similarity(
		string $first,
		string $second,
		bool $caseInsensitive = false
	): array {
		$matches = 0;
		$percent = 0.0;

		if ($caseInsensitive === true) {
			$first  = static::lower($first);
			$second = static::lower($second);
		}

		if (static::length($first) + static::length($second) > 0) {
			$pos1 = $pos2 = $max = 0;
			$len1 = static::length($first);
			$len2 = static::length($second);

			for ($p = 0; $p < $len1; ++$p) {
				for ($q = 0; $q < $len2; ++$q) {
					for (
						$l = 0;
						($p + $l < $len1) && ($q + $l < $len2) &&
						static::substr($first, $p + $l, 1) === static::substr($second, $q + $l, 1);
						++$l
					) {
						// nothing to do
					}

					if ($l > $max) {
						$max  = $l;
						$pos1 = $p;
						$pos2 = $q;
					}
				}
			}

			$matches = $max;

			if ($matches) {
				if ($pos1 && $pos2) {
					$similarity = static::similarity(
						static::substr($first, 0, $pos1),
						static::substr($second, 0, $pos2)
					);
					$matches += $similarity['matches'];
				}

				if (($pos1 + $max < $len1) && ($pos2 + $max < $len2)) {
					$similarity = static::similarity(
						static::substr($first, $pos1 + $max, $len1 - $pos1 - $max),
						static::substr($second, $pos2 + $max, $len2 - $pos2 - $max)
					);
					$matches += $similarity['matches'];
				}
			}

			$percent = ($matches * 200.0) / ($len1 + $len2);
		}

		return compact('matches', 'percent');
	}

	/**
	 * Convert a string to a safe version to be used in a URL
	 *
	 * @param string $string The unsafe string
	 * @param string $separator To be used instead of space and
	 *                          other non-word characters.
	 * @param string $allowed List of all allowed characters (regex)
	 * @param int $maxlength The maximum length of the slug
	 * @return string The safe string
	 */
	public static function slug(
		string|null $string,
		string|null $separator = null,
		string|null $allowed = null,
		int|false $maxlength = 128
	): string {
		$separator ??= static::$defaults['slug']['separator'];
		$allowed   ??= static::$defaults['slug']['allowed'];

		$string = trim($string ?? '');
		$string = static::lower($string);
		$string = static::ascii($string);

		// replace spaces with simple dashes
		$string = preg_replace(
			'![^' . $allowed . ']!i',
			$separator,
			$string
		);

		if (strlen($separator) > 0) {
			// remove double separators
			$string = preg_replace(
				'![' . preg_quote($separator) . ']{2,}!',
				$separator,
				$string
			);
		}

		// replace slashes with dashes
		$string = str_replace('/', $separator, $string);

		// trim leading and trailing non-word-chars
		$string = preg_replace('!^[^a-z0-9]+!', '', $string);
		$string = preg_replace('![^a-z0-9]+$!', '', $string);

		// cut the string after the given maxlength
		if ($maxlength !== false) {
			$string = static::short($string, $maxlength, '');
		}

		return $string;
	}

	/**
	 * Convert a string to snake case.
	 */
	public static function snake(
		string|null $value,
		string $delimiter = '_'
	): string {
		if (ctype_lower($value) === false) {
			$value = preg_replace('/\s+/u', '', ucwords($value));
			$value = preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value);
			$value = static::lower($value);
		}
		return $value;
	}

	/**
	 * Better alternative for explode()
	 * It takes care of removing empty values
	 * and it has a built-in way to skip values
	 * which are too short.
	 *
	 * @param string|array|null $string The string to split
	 * @param string $separator The string to split by
	 * @param int $length The min length of values.
	 * @return array An array of found values
	 */
	public static function split(
		string|array|null $string,
		string $separator = ',',
		int $length = 1
	): array {
		if (is_array($string) === true) {
			return $string;
		}

		// make sure $string is string
		$string ??= '';

		$parts = explode($separator, $string);
		$out   = [];

		foreach ($parts as $p) {
			$p = trim($p);
			if (
				static::length($p) > 0 &&
				static::length($p) >= $length
			) {
				$out[] = $p;
			}
		}

		return $out;
	}

	/**
	 * Checks if a string starts with the passed needle
	 */
	public static function startsWith(
		string|null $string,
		string $needle,
		bool $caseInsensitive = false
	): bool {
		if ($needle === '') {
			return true;
		}

		return static::position($string, $needle, $caseInsensitive) === 0;
	}

	/**
	 * Converts a string to studly caps case
	 * @since 3.7.0
	 *
	 * @param string $value The string to convert
	 */
	public static function studly(string|null $value): string
	{
		$value = str_replace(['-', '_'], ' ', $value);
		$value = ucwords($value);
		return str_replace(' ', '', $value);
	}

	/**
	 * A UTF-8 safe version of substr()
	 */
	public static function substr(
		string|null $string,
		int $start = 0,
		int|null $length = null
	): string {
		return mb_substr($string ?? '', $start, $length, 'UTF-8');
	}

	/**
	 * Replaces placeholders in string with values from the data array
	 *
	 * ```php
	 * echo Str::template('From {{ b }} to {{ a }}', ['a' => 'there', 'b' => 'here']);
	 * // output: From here to there
	 * ```
	 *
	 * @param string|null $string The string with placeholders
	 * @param array $data Associative array with placeholders as
	 *                    keys and replacements as values.
	 *                    Supports query syntax.
	 * @param array $options An options array that contains:
	 *                       - fallback: if a token does not have any matches
	 *                       - callback: to be able to handle each matching result
	 *                       - start: start placeholder
	 *                       - end: end placeholder
	 * @return string The filled-in string
	 */
	public static function template(
		string|null $string,
		array $data = [],
		array $options = []
	): string {
		$start    = $options['start'] ?? '{{1,2}';
		$end      = $options['end'] ?? '}{1,2}';
		$fallback = $options['fallback'] ?? null;
		$callback = $options['callback'] ?? null;

		if ($callback instanceof Closure === false) {
			$callback = null;
		}

		// make sure $string is string
		$string ??= '';

		return preg_replace_callback(
			'!' . $start . '(.*?)' . $end . '!',
			function (array $match) use ($data, $fallback, $callback) {
				$query = trim($match[1]);

				try {
					$result = Query::factory($query)->resolve($data);
				} catch (Throwable) {
					$result = null;
				}

				// if we don't have a result, use the fallback if given
				$result ??= $fallback;

				// callback on result if given
				if ($callback !== null) {
					$callback = $callback((string)$result, $query, $data);

					if ($result !== null || $callback !== '') {
						// the empty string came just from string casting,
						// keep the null value and ignore the callback result
						$result = $callback;
					}
				}

				// wihtout a result, keep the original placeholder
				return $result ?? $match[0];
			},
			$string
		);
	}

	/**
	 * Converts a filesize string with shortcuts
	 * like M, G or K to an integer value
	 */
	public static function toBytes(string $size): int
	{
		$size = trim($size);
		$last = strtolower($size[strlen($size) - 1] ?? '');
		$size = (int)$size;

		$size *= match ($last) {
			'g'     => 1024 * 1024 * 1024,
			'm'     => 1024 * 1024,
			'k'     => 1024,
			default => 1
		};

		return $size;
	}

	/**
	 * Convert the string to the given type
	 */
	public static function toType($string, $type)
	{
		if (is_string($type) === false) {
			$type = gettype($type);
		}

		return match ($type) {
			'array'           => (array)$string,
			'bool', 'boolean' => filter_var($string, FILTER_VALIDATE_BOOLEAN),
			'double', 'float' => (float)$string,
			'int', 'integer'  => (int)$string,
			default           => (string)$string
		};
	}

	/**
	 * Safe trim alternative
	 */
	public static function trim(string $string, string $trim = ' '): string
	{
		return static::rtrim(static::ltrim($string, $trim), $trim);
	}

	/**
	 * A UTF-8 safe version of ucfirst()
	 */
	public static function ucfirst(string|null $string): string
	{
		$first = static::substr($string, 0, 1);
		$rest  = static::substr($string, 1);
		return static::upper($first) . $rest;
	}

	/**
	 * A UTF-8 safe version of ucwords()
	 */
	public static function ucwords(string|null $string): string
	{
		return mb_convert_case($string ?? '', MB_CASE_TITLE, 'UTF-8');
	}

	/**
	 * Removes all html tags and encoded chars from a string
	 *
	 * ```php
	 * echo str::unhtml('some <em>crazy</em> stuff');
	 * // output: some uber crazy stuff
	 * ```
	 */
	public static function unhtml(string|null $string): string
	{
		return Html::decode($string);
	}

	/**
	 * Returns the beginning of a string until the given character
	 */
	public static function until(
		string $string,
		string $needle,
		bool $caseInsensitive = false
	): string {
		$position = static::position($string, $needle, $caseInsensitive);

		if ($position === false) {
			return '';
		}

		return static::substr($string, 0, $position + static::length($needle));
	}

	/**
	 * A UTF-8 safe version of strotoupper()
	 */
	public static function upper(string|null $string): string
	{
		return mb_strtoupper($string ?? '', 'UTF-8');
	}

	/**
	 * Creates a compliant v4 UUID
	 * Taken from: https://github.com/symfony/polyfill
	 * @since 3.7.0
	 */
	public static function uuid(): string
	{
		$uuid = bin2hex(random_bytes(16));

		return sprintf(
			'%08s-%04s-4%03s-%04x-%012s',
			// 32 bits for "time_low"
			substr($uuid, 0, 8),
			// 16 bits for "time_mid"
			substr($uuid, 8, 4),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			substr($uuid, 13, 3),
			// 16 bits:
			// * 8 bits for "clk_seq_hi_res",
			// * 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			hexdec(substr($uuid, 16, 4)) & 0x3fff | 0x8000,
			// 48 bits for "node"
			substr($uuid, 20, 12)
		);
	}

	/**
	 * The widont function makes sure that there are no
	 * typographical widows at the end of a paragraph –
	 * that's a single word in the last line
	 */
	public static function widont(string|null $string): string
	{
		// make sure $string is string
		$string ??= '';

		// Replace space between last word and punctuation
		$string = preg_replace_callback(
			'|(\S)\s(\S?)$|u',
			fn ($matches) => $matches[1] . '&nbsp;' . $matches[2],
			$string
		);

		// Replace space between last two words
		return preg_replace_callback('|(\s)(?=\S*$)(\S+)|u', function ($matches) {
			if (static::contains($matches[2], '-')) {
				$matches[2] = str_replace('-', '&#8209;', $matches[2]);
			}
			return '&nbsp;' . $matches[2];
		}, $string);
	}

	/**
	 * Wraps the string with the given string(s)
	 * @since 3.7.0
	 *
	 * @param string $string String to wrap
	 * @param string $before String to prepend
	 * @param string|null $after String to append (if different from `$before`)
	 */
	public static function wrap(
		string $string,
		string $before,
		string|null $after = null
	): string {
		return $before . $string . ($after ?? $before);
	}
}
