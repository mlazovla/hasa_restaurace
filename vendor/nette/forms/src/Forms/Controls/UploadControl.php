<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Forms\Controls;

use Nette;
use Nette\Http\FileUpload;


/**
 * Text box and browse button that allow users to select a file to upload to the server.
 */
class UploadControl extends BaseControl
{

	/**
	 * @param  string  label
	 * @param  bool  allows to upload multiple files
	 */
	public function __construct($label = NULL, $multiple = FALSE)
	{
		parent::__construct($label);
		$this->control->type = 'file';
		$this->control->multiple = (bool) $multiple;
	}


	/**
	 * This method will be called when the component (or component's parent)
	 * becomes attached to a monitored object. Do not call this method yourself.
	 * @param  Nette\ComponentModel\IComponent
	 * @return void
	 */
	protected function attached($form)
	{
		if ($form instanceof Nette\Forms\Form) {
			if ($form->getMethod() !== Nette\Forms\Form::POST) {
				throw new Nette\InvalidStateException('File upload requires method POST.');
			}
			$form->getElementPrototype()->enctype = 'multipart/form-data';
		}
		parent::attached($form);
	}


	/**
	 * Loads HTTP data.
	 * @return void
	 */
	public function loadHttpData()
	{
		$this->value = $this->getHttpData(Nette\Forms\Form::DATA_FILE);
		if ($this->value === NULL) {
			$this->value = new FileUpload(NULL);
		}
	}


	/**
	 * Returns HTML name of control.
	 * @return string
	 */
	public function getHtmlName()
	{
		return parent::getHtmlName() . ($this->control->multiple ? '[]' : '');
	}


	/**
	 * @return static
	 * @internal
	 */
	public function setValue($value)
	{
		return $this;
	}


	/**
	 * Has been any file uploaded?
	 * @return bool
	 */
	public function isFilled()
	{
		return $this->value instanceof FileUpload ? $this->value->isOk() : (bool) $this->value; // ignore NULL object
	}


	/**
	 * Have been all files succesfully uploaded?
	 * @return bool
	 */
	public function isOk()
	{
		return $this->value instanceof FileUpload
			? $this->value->isOk()
			: $this->value && array_reduce($this->value, function ($carry, $fileUpload) {
				return $carry && $fileUpload->isOk();
			}, TRUE);
	}

}
