<?php

namespace Idez\Caradhras\Enums;

enum MessageCode: int
{
    case InvalidDocument = 1000;
    case IrregularDocument = 1001;
    case IncorrectName = 1002;
    case IncorrectMother_Name = 1003;
    case IncorrectBirth_Date = 1004;
    case SanctionedDocument = 1005;
    case BlackListDocument = 1006;

    public function label(): string
    {
        return match ($this) {
            self::InvalidDocument => 'invalid_document',
            self::IrregularDocument => 'irregular_document',
            self::IncorrectName => 'incorrect_name',
            self::IncorrectMother_Name => 'incorrect_mother_name',
            self::IncorrectBirth_Date => 'incorrect_birth_date',
            self::SanctionedDocument => 'sanctioned_document',
            self::BlackListDocument => 'black_list_document',
        };
    }

    public function codes(): int
    {
        return match ($this) {
            self::InvalidDocument => 1000,
            self::IrregularDocument => 1001,
            self::IncorrectName => 1002,
            self::IncorrectMother_Name => 1003,
            self::IncorrectBirth_Date => 1004,
            self::SanctionedDocument => 1005,
            self::BlackListDocument => 1006,
        };
    }

    public static function caseByCode(int $code): MessageCode
    {
        return match ($code) {
            1000 => self::InvalidDocument,
            1001 => self::IrregularDocument,
            1002 => self::IncorrectName,
            1003 => self::IncorrectMother_Name,
            1004 => self::IncorrectBirth_Date,
            1005 => self::SanctionedDocument,
            1006 => self::BlackListDocument,
        };
    }
}
