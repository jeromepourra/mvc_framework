<?php

namespace core\router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
	public string $path;
	public array $methods;
	public array $patterns;

	/** @var Chunkpath[] */
	private array $chunks = [];

	public function __construct(string $path, array $methods = ["GET"], array $patterns = [])
	{
		$this->path = $path;
		$this->methods = $methods;
		$this->patterns = $patterns;

		// Parcours tous les chunk du path
		foreach (explode("/", $this->path) as $nIndex => $sChunk) {
			if ($this->isVariable($sChunk, $sName)) {
				// C'est une variable alors, défini le chunk comme tel
				// Ajoute son nom
				// Ajout son pattern qui devra match avec son chunk dans l'URI
				$oChunk = new Chunkpath($sChunk, $nIndex, true, $sName, $this->getChunkPattern($sName));
				array_push($this->chunks, $oChunk);
			} else {
				// C'est pas une variable
				// Son chunk dans l'URI devra match avec la string $sChunk
				$oChunk = new Chunkpath($sChunk, $nIndex);
				array_push($this->chunks, $oChunk);
			}
		}

	}

	/**
	 * Retourne true | false si le morceau du chemin est une variable.
	 * Dans le chemin: "/hello/{name}" le morceau "{name}" est considéré comme une variable du path
	 * 
	 * Le second paramètre est envoyé par référence, et sera assigné avec le nom de la variable ici "name"
	 */
	private function isVariable(string $sPiece, mixed &$sName): bool
	{
		$bMatch = preg_match("/\{([A-Za-z]+)\}/", $sPiece, $aMatches);
		if ($bMatch) {
			$sName = $aMatches[1];
		}
		return $bMatch;
	}

	private function getChunkPattern(string $sName): string
	{
		if (array_key_exists($sName, $this->patterns)) {
			// Un pattern correspondant au nom de la variable est trouvé
			return "/^" . $this->patterns[$sName] . "$/";
		}
		// Aucun pattern trouvé, renvois un pattern par défaut
		return "/^[A-Za-z0-9_-]+$/";

	}

	public function match(array $aRequestUri, string $sRequestMethod): bool
	{

		// La méthode HTTP n'est pas acceptée
		if (!$this->isMatchMethod($sRequestMethod)) {
			return false;
		}

		// Le nombre de chunks ne correspond pas a la route
		if (!$this->isMatchChunkCount($aRequestUri)) {
			return false;
		}

		foreach ($aRequestUri as $nIndex => $sRequestChunk) {

			$oChunk = $this->chunks[$nIndex];

			if ($oChunk->variable) {
				if (!$oChunk->isMatchPattern($sRequestChunk)) {
					return false;
				} else {
					// Sauvegarde la valeur du chunk
					$oChunk->setValue($sRequestChunk);
				}
			} else {
				if (!$oChunk->isMatch($sRequestChunk)) {
					return false;
				}
			}

		}

		return true;

	}

	/**
	 * Récupère un chunk depuis son nom
	 */
	public function getChunkFromName(string $sName): ?Chunkpath {
		foreach ($this->chunks as $oChunk) {
			if ($oChunk->isMatchName($sName)) {
				return $oChunk;
			}
		}
		return null;
	}

	private function isMatchMethod(string $sMethod): bool
	{
		return in_array($sMethod, $this->methods);
	}

	private function isMatchChunkCount(array $aRequest): bool
	{
		return count($aRequest) === count($this->chunks);
	}

}