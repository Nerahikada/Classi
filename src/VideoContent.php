<?php

/**
 *
 *  $$$$$$\  $$\                               $$\ 
 * $$  __$$\ $$ |                              \__|
 * $$ /  \__|$$ | $$$$$$\   $$$$$$$\  $$$$$$$\ $$\ 
 * $$ |      $$ | \____$$\ $$  _____|$$  _____|$$ |
 * $$ |      $$ | $$$$$$$ |\$$$$$$\  \$$$$$$\  $$ |
 * $$ |  $$\ $$ |$$  __$$ | \____$$\  \____$$\ $$ |
 * \$$$$$$  |$$ |\$$$$$$$ |$$$$$$$  |$$$$$$$  |$$ |
 *  \______/ \__| \_______|\_______/ \_______/ \__|
 *
 *
 * @author Nerahikada
 * @link https://twitter.com/Nerahikada
 *
 */

declare(strict_types=1);

namespace Classi;

class VideoContent extends Content{

	public function getType() : string{
		return 'video';
	}

	public function init() : void{}

	public function doHomework() : void{
		$response = $this->client->getHttpClient()->get($this->url);
		$body = (string) $response->getBody();

		$statusId = Utility::getStringBetween($body, 'gon.study_status_id=', ';');
		$contentId = Utility::getStringBetween($body, 'gon.content_id=', ';');
		$lectureId = Utility::getStringBetween($body, 'gon.lecture_id=', ';');
		$courseId = Utility::getStringBetween($body, 'gon.course_id=', ';');
		$metaId = Utility::getStringBetween($body, 'gon.meta_id=', ';');
		$mediaId = Utility::getStringBetween($body, 'gon.media_id=', ';');
		$userId = Utility::getStringBetween($body, 'gon.logica_user_id=', ';');
		$token = Utility::getStringBetween($body, 'gon.token=', ';');

		$postData = "native_app_name=&study_status_id={$statusId}&video_content_id={$contentId}&content_id={$contentId}&lecture_id={$lectureId}&course_id={$courseId}&player_insert_flag=true&meta_id={$metaId}&speed_list%5B1.0%5D={$mediaId}&media_id={$mediaId}&play_speed=1.0&logica_user_id={$userId}&token={$token}&current_time=0&is_from_top=false&ajax_flag=true&ajax_url=%2Fapi%2Fv1%2Fstudents%2Fvideo_complete&success=success";
		$response = $this->client->getHttpClient()->post('https://video.classi.jp/api/v1/students/videos/start_study', [
			'body' => $postData,
			'headers' => [
				'content-type': 'application/x-www-form-urlencoded;charset=UTF-8'
			]
		]);

		$body = (string) $response->getBody();

		$vsscId = Utility::getStringBetween($body, 'vssc_id":', ',');
		$studyType = Utility::getStringBetween($body, 'study_type":"', '"');

		$postData .= "&vssc_id={$vsscId}&study_type={$studyType}";
		$this->client->getHttpClient()->patch('https://video.classi.jp/api/v1/students/video_complete', [
			'body' => $postData,
			'headers' => [
				'content-type': 'application/x-www-form-urlencoded;charset=UTF-8'
			]
		]);

		Client::getLogger()->debug('Watched movie');
	}
}