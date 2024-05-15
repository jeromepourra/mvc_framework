<?php

class Entity {

	public function findOneById(string $sValue): static {
		return $this->findOneBy("id", $sValue);
	}

	public function findOneBy(string $sField, string $sValue): static {
		// FIX ME
		return $this;
	}

}