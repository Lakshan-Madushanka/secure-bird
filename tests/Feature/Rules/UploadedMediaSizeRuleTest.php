<?php

declare(strict_types=1);

use App\Rules\UploadedMediaSizeRule;

it('pass the rule when array is empty', function (): void {
    $files = [];

    $invalid = false;

    $rule = new UploadedMediaSizeRule(3601);

    $rule->validate('media', $files, function () use (&$invalid): void {
        $invalid = true;
    });

    expect($invalid)->toBeFalse();
});

it('pass the rule when type is not array', function (): void {
    $files = 'value';

    $invalid = false;

    $rule = new UploadedMediaSizeRule(3601);

    $rule->validate('media', $files, function () use (&$invalid): void {
        $invalid = true;
    });

    expect($invalid)->toBeFalse();
});

it('fail the rule when allowed limit exceeded', function (): void {
    $files = [
        \Illuminate\Http\UploadedFile::fake()->create('file1', 1200),
        \Illuminate\Http\UploadedFile::fake()->create('file1', 1200),
        \Illuminate\Http\UploadedFile::fake()->create('file1', 1200),
    ];

    $invalid = false;

    $rule = new UploadedMediaSizeRule(3601);

    $rule->validate('media', $files, function () use (&$invalid): void {
        $invalid = true;
    });

    expect($invalid)->toBeFalse();
});

it('pass the rule when allowed limit not exceeded', function (): void {
    $files = [
        \Illuminate\Http\UploadedFile::fake()->create('file1', 1200),
        \Illuminate\Http\UploadedFile::fake()->create('file1', 1200),
        \Illuminate\Http\UploadedFile::fake()->create('file1', 1200),
    ];

    $invalid = false;

    $rule = new UploadedMediaSizeRule(3600);

    $rule->validate('media', $files, function () use ($invalid): void {
        $invalid = false;
    });

    expect($invalid)->toBeFalse();
});
