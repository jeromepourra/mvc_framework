<?php

/*
 **************************************************
 ** functions
 **************************************************
 **
 ** toutes les fonctions globals de l'application
 **
 **************************************************
 */

/**
 * Retourne un htmlspecialchars (safe string) si une string passe en param
 * Retourne la valeur initiale si le param n'est pas une string
 * 
 * <code>
 * <?php
 * SafeHTML("<b>Inject</b>") // Output: &lt;b&gt;Inject&lt;/b&gt;
 * ?>
 * </code>
 */
function SafeHTML(mixed $data): mixed
{
	if (is_string($data)) {
		return htmlspecialchars($data);
	} else {
		return $data;
	}
}

// JSON
// ====

function EncodeJSON(mixed $data, bool $pretty = false, int $flags = 0): ?string
{
	if ($pretty) {
		$flags |= JSON_PRETTY_PRINT;
	}

	$json = json_encode($data, $flags);
	if (json_last_error() === JSON_ERROR_NONE) {
		return $json;
	}

	return null;
}

function Dumper(mixed $data)
{
	return EncodeJSON($data, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

// 
// Bufferisation de sortie
// =======================

function BufferOn(): void
{
	ob_start();
}

function BufferOff(): string
{
	return ob_get_clean();
}