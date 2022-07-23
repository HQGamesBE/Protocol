<?php
/*
 * Copyright (c) Jan Sohn.
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace nexusmc\protocol\tools\generate_create_static_methods;
use FilesystemIterator;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;


require dirname(__DIR__, 2) . '/vendor/autoload.php';
function generateBuildFunctionPHP(ReflectionClass $reflect, int $indentLevel, int $modifiers): array{
	$properties = [];
	$paramTags = [];
	$longestParamType = 0;
	foreach ($reflect->getProperties() as $property) {
		if ($property->getDeclaringClass()->getName() !== $reflect->getName()) {
			continue;
		}
		if (!$property->isPublic()) {
			continue;
		}
		$properties[$property->getName()] = $property;
		if (($phpDoc = $property->getDocComment()) !== false && preg_match('/@var ([A-Za-z\d\[\]\\\\]+)/', $phpDoc, $matches) === 1) {
			$paramTags[] = [$matches[1], $property->getName()];
			$longestParamType = max($longestParamType, strlen($matches[1]));
		}
	}
	$lines = [];
	$lines[] = "/**";
	$lines[] = " * @generate-build-method";
	$lines[] = " * Function build";
	$visibilityStr = match (true) {
		($modifiers & ReflectionMethod::IS_PUBLIC) !== 0 => "public",
		($modifiers & ReflectionMethod::IS_PRIVATE) !== 0 => "private",
		($modifiers & ReflectionMethod::IS_PROTECTED) !== 0 => "protected",
		default => throw new InvalidArgumentException("Visibility must be a ReflectionMethod visibility constant")
	};
	$funcStart = "#[Pure] $visibilityStr static function build(";
	$funcEnd = "): {$reflect->getShortName()}{";
	$params = [];
	foreach ($properties as $name => $reflectProperty) {
		$stringType = "";
		$propertyType = $reflectProperty->getType();
		if ($propertyType instanceof ReflectionNamedType) {
			$stringType = ($propertyType->allowsNull() ? "?" : "") . ($propertyType->isBuiltin() ? ""
					: "\\") . $propertyType->getName();
		} else if ($propertyType instanceof ReflectionUnionType) {
			$stringType = implode("|", array_map(fn(ReflectionNamedType $subType) => ($subType->isBuiltin() ? ""
					: "\\") . $subType->getName(), $propertyType->getTypes()));
		}
		$params[] = ($stringType !== "" ? "$stringType " : "") . "\$$name";
	}
	foreach ($params as $param) {
		$lines[] = " * @var $param";
	}
	$lines[] = " * @return {$reflect->getShortName()}";
	$lines[] = " */";
	if (count($params) <= 6) {
		$lines[] = $funcStart . implode(", ", $params) . $funcEnd;
	} else {
		$lines[] = $funcStart;
		foreach ($params as $param) {
			$lines[] = "\t$param,";
		}
		$lines[] = $funcEnd;
	}
	if (count($params) > 0) {
		$lines[] = "\t\$result = new self;";
		foreach ($properties as $name => $reflectProperty) {
			$lines[] = "\t\$result->$name = \$$name;";
		}
		$lines[] = "\treturn \$result;";
	} else {
		$lines[] = "\treturn new self;";
	}
	$lines[] = "}";
	return array_map(fn(string $line) => str_repeat("\t", $indentLevel) . $line, $lines);
}

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__, 2) . '/src/php/network/protocol', FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME)) as $file) {
	if (!str_ends_with($file, ".php")) {
		continue;
	}
	$contents = file_get_contents($file);
	if ($contents === false) {
		throw new RuntimeException("Failed to get contents of $file");
	}
	if (preg_match("/(*ANYCRLF)^namespace (.+);$/m", $contents, $matches) !== 1 || preg_match('/(*ANYCRLF)^((final|abstract)\s+)?class /m', $contents) !== 1) {
		continue;
	}
	$shortClassName = basename($file, ".php");
	$className = $matches[1] . "\\" . $shortClassName;
	if (!class_exists($className)) {
		continue;
	}
	$reflect = new ReflectionClass($className);
	echo $reflect->getShortName() . ": ";
	if (!$reflect->hasMethod("build")) {
		echo "skipped: no build() found\n";
		continue;
	}
	$createReflect = $reflect->getMethod("build");
	if ($createReflect->getDeclaringClass()->getName() !== $reflect->getName()) {
		echo "skipped: build() declared by parent class\n";
		continue;
	}
	$docComment = $createReflect->getDocComment();
	if ($docComment === false || preg_match('/@generate-build-method\s/', $docComment) !== 1) {
		echo "skipped: @generate-build-method not found in build() doc comment\n";
		continue;
	}
	$lines = preg_split("/(*ANYCRLF)\n/", $contents);
	$docCommentLines = preg_split("/(*ANYCRLF)\n/", $docComment);
	$beforeLines = array_slice($lines, 0, $createReflect->getStartLine() - 1 - count($docCommentLines));
	$afterLines = array_slice($lines, $createReflect->getEndLine());
	file_put_contents($file, implode("\n", $beforeLines) . "\n" . implode("\n", generateBuildFunctionPHP($reflect, 1, $createReflect->getModifiers())) . "\n" . implode("\n", $afterLines));
	echo "successfully\n";
}

