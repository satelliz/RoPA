<?php
/*
  * This file is part of the Record of processing activities project.
  * Its original location is https://github.com/Safran/RoPA
  * 
  * SPDX-License-Identifier: GPL-3.0-only
  */






namespace App\Notifications;

use App\Models\Statement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatementCreated extends Notification
{
	use Queueable;

	protected $statement;

	public function __construct(Statement $statement)
	{
		$this->statement = $statement;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable)
	{
		return [ 'database', 'mail' ];
	}


	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			'id'      => $this->statement->id,
			'title'   => $this->statement->get('name'),
			'user_id' => $this->statement->owner_id,
		];
	}



	public function toMail($notifiable)
	{
		return ( new MailMessage )->markdown('emails.statementcreated', [
			'notifiable' => $notifiable,
			'url'        => url('/'),
			'statement' => $this->statement
		])->subject(__('emails/statementvalidated.subject', [ 'fullname' => $notifiable->full_name ]));
	}
}
