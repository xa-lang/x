<?php

enum CharCode: int
{
    case NULL                 = 0x00;
    case BELL                 = 0x07;
    case BACKSPACE            = 0x08;
    case CHARACTER_TABULATION = 0x09;
    case LINE_FEED            = 0x0A;
    case LINE_TABULATION      = 0x0B;
    case FORM_FEED            = 0x0C;
    case CARRIAGE_RETURN      = 0x0D;
    case SPACE                = 0x20;
    case EXCLAMATION_MARK     = 0x21;
    case QUOTATION_MARK       = 0x22;
    case NUMBER_SIGN          = 0x23;
    case DOLLAR_SIGN          = 0x24;
    case PERCENT_SIGN         = 0x25;
    case AMPERSAND            = 0x26;
    case APOSTROPHE           = 0x27;
    case LEFT_PARANTHESIS     = 0x28;
    case RIGHT_PARANTHESIS    = 0x29;
    case ASTERISK             = 0x2A;
    case PLUS_SIGN            = 0x2B;
    case COMMA                = 0x2C;
    case HYPHEN_MINUS         = 0x2D;
    case FULL_STOP            = 0x2E;
    case SOLIDUS              = 0x2F;
    case DIGIT_ZERO           = 0x30;
    case DIGIT_ONE            = 0x31;
    case DIGIT_TWO            = 0x32;
    case DIGIT_THREE          = 0x33;
    case DIGIT_FOUR           = 0x34;
    case DIGIT_FIVE           = 0x35;
    case DIGIT_SIX            = 0x36;
    case DIGIT_SEVEN          = 0x37;
    case DIGIT_EIGHT          = 0x38;
    case DIGIT_NINE           = 0x39;
    case COLON                = 0x3A;
    case SEMICOLON            = 0x3B;
    case LESS_THAN_SIGN       = 0x3C;
    case EQUAL_SIGN           = 0x3D;
    case GREATER_THAN_SIGN    = 0x3E;
    case QUESTION_MARK        = 0x3F;
    case COMMERCIAL_AT        = 0x40;
    case CAPITAL_LETTER_A     = 0x41;
    case CAPITAL_LETTER_B     = 0x42;
    case CAPITAL_LETTER_C     = 0x43;
    case CAPITAL_LETTER_D     = 0x44;
    case CAPITAL_LETTER_E     = 0x45;
    case CAPITAL_LETTER_F     = 0x46;
    case CAPITAL_LETTER_G     = 0x47;
    case CAPITAL_LETTER_H     = 0x48;
    case CAPITAL_LETTER_I     = 0x49;
    case CAPITAL_LETTER_J     = 0x4A;
    case CAPITAL_LETTER_K     = 0x4B;
    case CAPITAL_LETTER_L     = 0x4C;
    case CAPITAL_LETTER_M     = 0x4D;
    case CAPITAL_LETTER_N     = 0x4E;
    case CAPITAL_LETTER_O     = 0x4F;
    case CAPITAL_LETTER_P     = 0x50;
    case CAPITAL_LETTER_Q     = 0x51;
    case CAPITAL_LETTER_R     = 0x52;
    case CAPITAL_LETTER_S     = 0x53;
    case CAPITAL_LETTER_T     = 0x54;
    case CAPITAL_LETTER_U     = 0x55;
    case CAPITAL_LETTER_V     = 0x56;
    case CAPITAL_LETTER_W     = 0x57;
    case CAPITAL_LETTER_X     = 0x58;
    case CAPITAL_LETTER_Y     = 0x59;
    case CAPITAL_LETTER_Z     = 0x5A;
    case LEFT_SQUARE_BRACKET  = 0x5B;
    case REVERSE_SOLIDUS      = 0x5C;
    case RIGHT_SQUARE_BRACKET = 0x5D;
    case CIRCUMFLEX_ACCENT    = 0x5E;
    case LOW_LINE             = 0x5F;
    case GRAVE_ACCENT         = 0x60;
    case SMALL_LETTER_A       = 0x61;
    case SMALL_LETTER_B       = 0x62;
    case SMALL_LETTER_C       = 0x63;
    case SMALL_LETTER_D       = 0x64;
    case SMALL_LETTER_E       = 0x65;
    case SMALL_LETTER_F       = 0x66;
    case SMALL_LETTER_G       = 0x67;
    case SMALL_LETTER_H       = 0x68;
    case SMALL_LETTER_I       = 0x69;
    case SMALL_LETTER_J       = 0x6A;
    case SMALL_LETTER_K       = 0x6B;
    case SMALL_LETTER_L       = 0x6C;
    case SMALL_LETTER_M       = 0x6D;
    case SMALL_LETTER_N       = 0x6E;
    case SMALL_LETTER_O       = 0x6F;
    case SMALL_LETTER_P       = 0x70;
    case SMALL_LETTER_Q       = 0x71;
    case SMALL_LETTER_R       = 0x72;
    case SMALL_LETTER_S       = 0x73;
    case SMALL_LETTER_T       = 0x74;
    case SMALL_LETTER_U       = 0x75;
    case SMALL_LETTER_V       = 0x76;
    case SMALL_LETTER_W       = 0x77;
    case SMALL_LETTER_X       = 0x78;
    case SMALL_LETTER_Y       = 0x79;
    case SMALL_LETTER_Z       = 0x7A;
    case L_CURLY_BRACKET      = 0x7B;
    case vERTICAL_LINE        = 0x7C;
    case R_CURLY_BRACKET      = 0x7D;
    case TILDE                = 0x7E;

    public function getText()
    {
        return chr($this->value);
    }

    public function isAlpha()
    {
        return ($this->value >= self::CAPITAL_LETTER_A->value && $this->value <= self::CAPITAL_LETTER_Z->value)
            || ($this->value >= self::SMALL_LETTER_A->value && $this->value <= self::SMALL_LETTER_Z->value);
    }

    public function isDigit()
    {
        return $this->value >= self::DIGIT_ZERO->value
            && $this->value <= self::DIGIT_NINE->value;
    }

    public function isLineFeed()
    {
        return $this == self::LINE_FEED;
    }

    public function isWhitespace()
    {
        return ctype_space($this->getText());
    }
}

class Scanner
{
    /**
     * The program source code
     *
     * @var string
     */
    private string $source;

    /**
     * Current postion of the scanner in the source code
     *
     * @var int
     */
    private int $position;

    /**
     * Current line of the scanner position in the source code
     *
     * @var int
     */
    private int $line;

    /**
     * Current column of the scanner position in the source code
     *
     * @var int
     */
    private int $column;

    /**
     * The source code char length
     *
     * @var int
     */
    private int $length;

    /**
     * The current dirty lexeme
     *
     * @var string
     */
    private string $lexeme;

    /**
     * The current dirty lexeme line
     *
     * @var int
     */
    private int $lexemeLine;

    private int $lexemeColumn;

    public function __construct($source)
    {
        $this->source = $source;
        $this->position = 0;
        $this->lexemeLine   = $this->line   = 1;
        $this->lexemeColumn = $this->column = 1;
        $this->length = strlen($source);
        $this->lexeme = "";
    }

    public function consume()
    {
        return $this->move(true);
    }

    public function getLine($num = null)
    {
        $src = "";
        $num ??= $this->line;
        $curr = 1;

        if ($num < $curr) {
            return $src;
        }

        foreach (explode("\n", $this->source) as $line) {
            if ($num == $curr++) {
                $src = "$line\n";
                break;
            }
        }

        return $src;
    }

    public function getLexemeInfo()
    {
        $i = [
            'value' => $this->lexeme,
            'line' => $this->lexemeLine,
            'column' => $this->lexemeColumn,
        ];
        $this->lexeme = "";
        $this->lexemeLine = $this->line;
        $this->lexemeColumn = $this->column;

        return $i;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getPoint()
    {
        return ['line' => $this->line, 'column' => $this->column];
    }

    public function isEof()
    {
        return $this->position >= $this->length;
    }

    public function move(bool $shouldAppend = false)
    {
        if ($this->position < $this->length) {
            $charCode = $this->peek();
            $this->position++;
            if ($charCode && $charCode->isLineFeed()) {
                $this->line++;
                $this->column = 1;
            } else {
                $this->column++;
            }

            if ($shouldAppend) {
                $this->lexeme .= $charCode->getText();
            }
            return $charCode;
        }

        return null;
    }

    public function peek($offset = 0)
    {
        $pos = $this->position + $offset;
        if ($pos < $this->length) {
            return CharCode::tryFrom(ord($this->source[$pos]));
        }
        return null;
    }

    public function skipWhitespace()
    {
        while (!$this->isEof()) {
            if ($this->peek()->isWhitespace()) {
                $this->consume();
            } else {
                break;
            }
        }
        $this->getLexemeInfo();
    }
}

/**
 * Token Type enum
 */
enum TokenType: int
{
    case UNKNOWN    = 0;
    case COMMENT    = 1;
    case EOF        = 2;
    case RAISED     = 3;
    case INTEGER    = 4;
    case FLOAT      = 5;
    case IDENTIFIER = 6;
    case KEYWORD    = 7;

    public function format(array $token)
    {
        if (!isset($token['type']) || (self::tryFrom($token['type']) !== $this)) {
            return '';
        }
        if (empty($token['value']) && (self::tryFrom($token['type']) !== self::EOF)) {
            return '';
        }
        if (empty($token['line']) || empty($token['column'])) {
            return '';
        }

        return join(
            ", ",
            [
                "TYPE: {$this->getName()}",
                ($this === self::EOF) ? "VALUE: NULL" : "VALUE: {$token['value']}",
                "POINT: [{$token['line']} {$token['column']}]",
            ]
        );
    }

    public function getName()
    {
        return "T_$this->name";
    }

    public function isError() {
        return $this == self::RAISED;
    }

    public static function getType(null|string $lexeme)
    {
        if ($lexeme == null) {
            return self::EOF;
        }

        if (is_numeric(str_replace('_', '', $lexeme))) {
            return str_contains($lexeme, 'E') || str_contains($lexeme, '.')
                ? self::FLOAT
                : self::INTEGER;
        }

        if (in_array($lexeme, ['any', 'boolean', 'byte', 'character', 'float', 'integer', 'null', 'void'])) {
            return self::KEYWORD;
        }

        if (preg_match('/^[A-Za-z_]([A-Za-z0-9_]*)$/', $lexeme)) {
            return self::IDENTIFIER;
        }

        if (preg_match('/^[;]/', $lexeme)) {
            return self::COMMENT;
        }

        return self::UNKNOWN;
    }
}

class RaisedException extends Exception
{
    private bool $shouldDescribe;

    public function __construct(string $title = "", bool $shouldDescribe = true)
    {
        $this->message = $title;
        $this->shouldDescribe = $shouldDescribe;
    }

    public function getSummary(Lexer $lexer): string
    {
        $info = $lexer->getInfo();
        $infoList = array_merge(
            $info['point'],
            $info
        );
        unset($infoList['point']);
        $message = str_replace(
            array_map(
                fn ($k) => "%{{$k}}",
                array_keys($infoList)
            ),
            array_values($infoList),
            $this->message,
        );
        if ($this->shouldDescribe) {
            $srcLine = $lexer->getCurrentLine();
            $times = $infoList['column'] + strlen($infoList['line']) + 1;
            $htimes = strlen($message);
            $srcLineDescr = "\n{$infoList['line']}| $srcLine";
            $hrLine = str_repeat("-", $htimes);
            $errColPos = str_repeat(" ", $times)."^";
            return "$message\n$hrLine$srcLineDescr$errColPos\n";
        }
        return $message;
    }
}

class Lexer
{
    /**
     * Lexer Scanner
     *
     * @var Scanner
     */
    private Scanner $scanner;

    /**
     * The source program tokens sequence
     *
     * @var array
     */
    private array $tokens;

    public function __construct(string $source)
    {
        $this->scanner = new Scanner($source);
        $this->tokens = [];
    }

    public function generateTokensOut()
    {
        $tokens = $this->tokenize();
        $tformatList = [];

        foreach ($tokens as $token) {
            $ttype = TokenType::tryFrom($token['type']);
            if ($ttype && $ttype->isError()) {
                return $token['value'];
            } elseif (!$ttype) {
                continue;
            }
            $tformatList[] = $ttype->format($token);
        }
        return join("\n", $tformatList);
    }

    public function getInfo()
    {
        return [
            'char' => $this->scanner->peek()->getText(),
            'point' => $this->getPoint(),
            'position' =>  $this->scanner->getPosition(),
        ];
    }

    public function getCurrentLine()
    {
        return $this->getLine($this->getPoint()['line']);
    }

    public function getLine($num)
    {
        return $this->scanner->getLine($num);
    }

    public function getPoint()
    {
        return $this->scanner->getPoint();
    }

    public function nextToken()
    {
        $cc = $this->scanner->peek();

        if ($cc == CharCode::SEMICOLON) {
            return $this->scanComment();
        } elseif ($cc->isDigit()) {
            return $this->scanNumber();
        } elseif ($cc->isAlpha() || $cc == CharCode::LOW_LINE) {
            return $this->scanWord();
        }

        throw new RaisedException("Unexpected character '%{char}' on line %{line}");
    }

    private function scanComment()
    {
        while (!$this->scanner->isEof() && !$this->scanner->peek()->isLineFeed()) {
            $this->scanner->consume();
        }
        return array_merge([
            'type' => TokenType::COMMENT->value,
        ], $this->scanner->getLexemeInfo());
    }

    private function scanNumber()
    {
        // Consume *1(0-9) *(0-9_) +(0-9) ?("."  +(0-9)) +(0-9) ?(E ?[+-] +(0-9))
        $this->scanner->consume();
        $cc = $this->scanner->peek();

        // Consume *(0-9_) +(0-9)
        while (!$this->scanner->isEof() && ($cc->isDigit() || $cc == CharCode::LOW_LINE)) {
            if ($cc == CharCode::LOW_LINE && !$this->scanner->peek(1)?->isDigit()) {
                throw new RaisedException("Unexpected character '%{char}' on line %{line}");
            }
            $this->scanner->consume();
            $cc = $this->scanner->peek();
        }

        // Check ?("."  +(0-9))
        if (!$this->scanner->isEof() && $this->scanner->peek() == CharCode::FULL_STOP) {
            $this->scanner->consume();

            if (!$this->scanner->isEof() && !$this->scanner->peek()->isDigit()) {
                throw new RaisedException("Unexpected character '%{char}' on line %{line}");
            }

            while (!$this->scanner->isEof() && $this->scanner->peek()->isDigit()) {
                $this->scanner->consume();
            }
        }

        // Check ?(E ?[+-] +(0-9))
        if (!$this->scanner->isEof() && ($this->scanner->peek() == CharCode::CAPITAL_LETTER_E)) {
            $this->scanner->consume();

            $cc = $this->scanner->peek();
            if (!$this->scanner->isEof() && ($cc == CharCode::PLUS_SIGN || $cc == CharCode::HYPHEN_MINUS)) {
                $this->scanner->consume();
            }

            if (!$this->scanner->isEof() && !$this->scanner->peek()->isDigit()) {
                throw new RaisedException("Unexpected character '%{char}' on line %{line}");
            }

            while (!$this->scanner->isEof() && $this->scanner->peek()->isDigit()) {
                $this->scanner->consume();
            }
        }

        $lInfo = $this->scanner->getLexemeInfo();
        return array_merge(
            ['type' => TokenType::getType($lInfo['value'])->value],
            $lInfo
        );
    }

    private function scanWord()
    {
        // Consume *1(A-Za-z) *(0-9A-Za-z_)
        $this->scanner->consume();

        $cc = $this->scanner->peek();
        while (!$this->scanner->isEof() && ($cc->isAlpha() || $cc->isDigit() || $cc == CharCode::LOW_LINE)) {
            $this->scanner->consume();
            $cc = $this->scanner->peek();
        }

        $lInfo = $this->scanner->getLexemeInfo();
        return array_merge(
            ['type' => TokenType::getType($lInfo['value'])->value],
            $lInfo
        );
    }

    public function tokenize()
    {
        while (!$this->scanner->isEof()) {
            $this->scanner->skipWhiteSpace();

            if ($this->scanner->isEof()) {
                break;
            }

            try {
                $token = $this->nextToken();
            } catch (RaisedException $re) {
                $this->tokens = [];
                $point = $this->getPoint();
                $this->tokens[] = [
                    'type' => TokenType::RAISED->value,
                    'value' => $re->getSummary($this),
                    'line' => $point['line'],
                    'column' => $point['column'],
                ];
                return $this->tokens;
            }
            if ($token) {
                $this->tokens[] = $token;
            }
        }
        $point = $this->getPoint();
        $this->tokens[] = [
            'type' => TokenType::EOF->value,
            'value' => '',
            'line' => $point['line'],
            'column' => $point['column'],
        ];

        return $this->tokens;
    }
}
