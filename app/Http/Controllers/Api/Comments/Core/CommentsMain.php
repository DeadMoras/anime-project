<?php

namespace App\Http\Controllers\Api\Comments\Core;

use App\Models\Seo;

class CommentsMain
{
    /**
     * @param string $modelName
     * @param string $title - seo title, because we need know entity_id from this table
     *
     * @return array
     */
    public function getComments(string $modelName, string $title)
    {
        $animeId = $this->entityIdFromSeo($title);

        if ( ! $animeId) {
            return ['error' => 'We doesn\'t have anime with this id'];
        }

        $model = "\\App\\Models\\".$modelName;

        $data = $model::with(
            [
                'user',
                'user.userInfo',
                'user.userImage',
            ])
            ->where('post_entity_id', $animeId)
            ->take(20)
            ->get();

        if ( ! $data) {
            return null;
        }

        $response = $this->parseComments($data);

        return $response;
    }

    /**
     * @param string $modelName
     * @param array  $data
     *
     * @return array
     */
    public function addComment(string $modelName, array $data)
    {
        $animeId = $this->entityIdFromSeo($data['post_title']);

        if ( ! $animeId) {
            return ['error' => 'We doesn\'t have anime with this id'];
        }

        $model = "\\App\\Models\\".$modelName;

        $model::insert(
            [
                'post_entity_id'    => $animeId,
                'user_entity_id'    => $data['user_id'],
                'body'              => $data['comment'],
                'review'            => $data['review'] == false
                    ? 0
                    : 1,
                'answer_to_user_id' => $data['reply_to_user_id'],
                'answer_comment_id' => $data['reply_to_comment'],
            ]);

        $dbData = $model::with(
            [
                'user',
                'user.userInfo',
                'user.userImage',
            ])
            ->where('post_entity_id', $animeId)
            ->where('user_entity_id', $data['user_id'])
            ->take(1)
            ->orderBy('created_at', 'desc')
            ->get();

        $response = $this->parseComments($dbData);

        return $response;
    }

    /**
     * @param string $title
     */
    private function entityIdFromSeo(string $title)
    {
        return Seo::where('path', $title)
            ->pluck('entity_id')
            ->first();
    }

    /**
     * @param      $data
     *
     * @return array
     */
    private function parseComments($data)
    {
        $response = [];

        foreach ($data as $k => $v) {
            $response[] = [
                'comment_id'          => $v->id,
                'review'              => $v->review == 0
                    ? false
                    : true,
                'comment'             => $v->body,
                'answer_comment_id'   => $v->answer_comment_id,
                'answer_comment_user' => $v->answer_to_user_id,
                'created_at'          => $v->created_at,
                'user'                => [
                    'id'         => $v->user->id,
                    'role'       => 0 == $v->user->role
                        ? 'user'
                        : (1 == $v->user->role
                            ? 'redactor'
                            : 'admin'),
                    'reputation' => $v->user->reputation,
                    'login'      => $v->user->userInfo->login,
                    'sex'        => $v->user->userInfo->sex == 0
                        ? 'man'
                        : 'wooman',
                    'avatar'     => env('api_url').'/images/user/'.$v->user->userImage[0]->name,
                ],
            ];
        }

        return $response;
    }
}