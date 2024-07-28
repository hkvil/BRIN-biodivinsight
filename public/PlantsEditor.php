<?php

// DataTables PHP library
include("../lib/DataTables.php");

// Alias Editor classes so they are easy to use
use DataTables\Editor;
use DataTables\Editor\Field;
use DataTables\Editor\Format;
use DataTables\Editor\Mjoin;
use DataTables\Editor\Options;
use DataTables\Editor\Upload;
use DataTables\Editor\Validate;
use DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst($db, 'plants')
    ->fields(
        Field::inst('ID')
            ->validator(Validate::numeric())
            ->setFormatter(Format::ifEmpty(null)),
        Field::inst('species_name')
            ->validator(Validate::notEmpty(ValidateOptions::inst()
                ->message('A species name is required'))),
        Field::inst('common_name')
            ->validator(Validate::notEmpty(ValidateOptions::inst()
                ->message('A common name is required'))),
        Field::inst('timestamp')
            ->validator(Validate::dateFormat('Y-m-d H:i:s', ValidateOptions::inst()
                ->message('Please enter a valid timestamp')))
            ->getFormatter(Format::dateSqlToFormat('Y-m-d H:i:s'))
            ->setFormatter(Format::dateFormatToSql('Y-m-d H:i:s'))
    )
    ->debug(true)
    ->process($_POST)
    ->json();