<?php

namespace Idez\Caradhras\Enums;

enum MessageCode: string
{
    case InvalidDocument = 'invalid_document';
    case IrregularDocument = 'irregular_document';
    case IncorrectName = 'incorrect_name';
    case IncorrectMother_Name = 'incorrect_mother_name';
    case IncorrectBirth_Date = 'incorrect_birth_date';
    case SanctionedDocument = 'sanctioned_document';
    case BlackListDocument = 'black_list_document';

    public function code(): int
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
