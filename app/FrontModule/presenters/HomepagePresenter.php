<?php

namespace App\FrontModule\Presenters;

use Nette,
    App\Model;

use Nette\Database\Context;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	/**
	 * @var Model\Article
	 * @inject
	 */
	public $article;

	public function renderDefault()
	{
		$this->template->article = $this->article->createSelectionInstance()->where('visible = 1');
	}

}
