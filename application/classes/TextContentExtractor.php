<?php


class TextContentExtractor implements IContentExtract {
	function getContents($filePath) {
		return file_get_contents($filePath);
	}
}
