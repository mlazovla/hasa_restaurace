<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Article edit presenter.
 */
class ArticlePresenter extends BasePresenter
{
	const FILE_PATH = 'article/';

	/**
	 * @var Model\Article
	 * @inject
	 */
	public $article;


	public function renderDefault()
	{
		$this->template->article = $this->article->order('order ASC');
	}

	/**
	 * @param $id
	 */
	public function actionEdit($id) {
		$article = $this->article->get($id);
		if(!$article) {
			$this->error('Data nebyla nalezena v databázi.', 404);
		}
		$defaults = $article->toArray();
		$defaults['parent_id'] = ($defaults['parent_id']) ? $defaults['parent_id'] : '';
		$this['articleForm']->setDefaults($defaults);

	}

	protected function createComponentArticleForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addText('menu', 'Položka menu:')
			->setRequired();

		$form->addText('name', 'Nadpis:');

		$form->addText('subname', 'Podnadpis:');

		$article = clone $this->article;
		$parents = $article->where('parent_id', null)->fetchPairs('id', 'menu');
		$parents[''] = '--- Hlavní nabídka ---';
		$articleId = $this->getParameter('id');
		if ($articleId && isset($parents[$articleId])) {
			unset($parents[$articleId]);
		}


		$form->addSelect('parent_id', 'Nadřazená položka menu', $parents)->setDefaultValue('');

		$form->addTextArea('text', 'Článek:')
			->setAttribute('class', 'tinyMCE');

		$form->addCheckbox('visible', 'Zobrazit v menu:')
			->setDefaultValue(true);

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->addText('backgroundColor', 'Barva pozadí')
			->addRule($form::MAX_LENGTH, 'Definujte barvu například pomocí rgba(255,127,0,1)',31);

		$form->onSuccess[] = array($this, 'articleFormSucceeded');

		return $form;
	}


	public function articleFormSucceeded(UI\Form $form, $values)
	{
		$articleData = [
			'menu' => $values['menu'],
			'name' => $values['name'],
			'subname' => $values['subname'],
			'text' => $values['text'],
			'visible' => $values['visible'],
			'parent_id' => ($values['parent_id']) ? $values['parent_id'] : null,
			'backgroundColor' => ($values['backgroundColor']) ? $values['backgroundColor'] : null,
		];

		$articleId = $this->getParameter('id');
		if ($articleId) {
			$article = $this->article->get($articleId);
			if (!$article) {
				$this->error('Data nebyla nalezena v databázi.', '404');
			} else {
				$article->update($articleData);
			}
			$this->flashMessage('Změny uloženy.', 'success');

		} else {
			$articleData['order'] = $this->article->createSelectionInstance()->max('order') + 1;
			$article = $this->article->insert($articleData);
			$this->flashMessage('Článek vložen do databáze.', 'success');
		}



		$this->redirect('edit', $article->id);
	}

	/**
	 * Delete Article by id
	 *
	 * @param $id
	 */
	public function actionDelete($id)
	{
		$this->article->get($id)->delete();
		$this->flashMessage('Článek odstraněn.', 'success');
		$this->redirect('default');
	}

	public function actionMoveUp($id)
	{
		$article = $this->article->get($id);
		$article2 = null;
		foreach ($this->article->createSelectionInstance()->where('order < '.$article->order)->order('order DESC')->limit(1) as $i) {
			$article2 = $i;
		}
		if ($article2 == null) {
			$this->flashMessage('Položka je již jako první.', 'warning');
			$this->redirect('default');
		}
		$tmp = $article->order;
		$article->update(['order' => $article2->order]);
		$article2->update(['order' => $tmp]);

		$this->flashMessage('Položka posunuta nahoru.', 'success');
		$this->redirect('default');
	}

	public function actionMoveDown($id)
	{
		$article = $this->article->get($id);
		$article2 = null;
		foreach ($this->article->createSelectionInstance()->where('order > '.$article->order)->order('order ASC')->limit(1) as $i) {
			$article2 = $i;
		}
		if ($article2 == null) {
			$this->flashMessage('Položka je již jako poslední.', 'warning');
			$this->redirect('default');
		}
		$tmp = $article->order;
		$article->update(['order' => $article2->order]);
		$article2->update(['order' => $tmp]);

		$this->flashMessage('Položka posunuta dolů.', 'success');
		$this->redirect('default');
	}
}
