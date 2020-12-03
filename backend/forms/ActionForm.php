<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class ActionForm extends Model
{

	/**
     * Returns the errors for all attributes or a single attribute.
     * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
     * @property array An array of errors for all attributes. Empty array is returned if no error.
     * The result is a two-dimensional array. See [[getErrors()]] for detailed description.
     * @return array errors for all attributes or the specified attribute. Empty array is returned if no error.
     * Note that when returning errors for all attributes, the result is a two-dimensional array, like the following:
     *
     * ```php
     * [
     *     'username' => [
     *         'Username is required.',
     *         'Username must contain only word characters.',
     *     ],
     *     'email' => [
     *         'Email address is invalid.',
     *     ]
     * ]
     * ```
     *
     * @see getFirstErrors()
     * @see getFirstError()
     */
	public function getFirstErrorMessage($attribute = null)
	{
		if (!$this->hasErrors()) return '';
		if ($attribute) return $this->getFirstError($attribute);
		$errors = $this->getErrorSummary(true);
		return reset($errors);
	}
}