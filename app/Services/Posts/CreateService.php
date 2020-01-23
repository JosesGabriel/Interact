<?php


namespace App\Services\Posts;

use App\Events\Posts\UserPostedEvent;
use App\Services\Attachments\CreateManyService;
use App\Services\BaseService;
use App\Data\Repositories\Posts\PostRepository;
use App\Services\Tags\CreateService as CreateTagService;

/**
 * Class CreateService
 *
 * @package App\Services\Posts
 */
class CreateService extends BaseService
{
    /**
     * @var CreateManyService
     */
    private $create_attachments;

    /**
     * @var CreateTagService
     */
    private $create_tag;

    /**
     * @var PostRepository
     */
    protected $post_repo;

    /**
     * CreateService constructor.
     * @param CreateManyService $createManyService
     * @param CreateTagService $createTagService
     * @param PostRepository $postRepository
     */
    public function __construct(
        CreateManyService $createManyService,
        CreateTagService $createTagService,
        PostRepository $postRepository
    ){
        $this->create_attachments = $createManyService;
        $this->create_tag = $createTagService;
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return \App\Data\Repositories\Attachments\AttachmentRepository|PostRepository|CreateManyService|CreateService
     */
    public function handle(array $data) : object
    {
        //region Data validation
        $valid_statuses = array_keys(config('arbitrage.posts.model.status'));

        if (!isset($data['status']) ||
            !in_array($data['status'], $valid_statuses)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The status is not set or invalid.',
            ]);
        }

        $valid_visibility = array_keys(config('arbitrage.posts.model.visibility'));

        if (!isset($data['visibility']) ||
            !in_array($data['visibility'], $valid_visibility)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The visibility is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->post_repo->create($data);

        if ($response->isError()) {
            return $response;
        }

        $post = ($response->getDataByKey('post'))->attributesToArray();
        $post_model = $response->getDataByKey('post');

        //region Create Attachments
        if (isset($data['attachments'])) {
            $attachments_data['attachable_id'] = $post['id'];
            $attachments_data['attachable_type'] = config('arbitrage.attachments.model.attachable_type.post.value');
            $attachments_data['user_id'] = $post['user_id'];
            $attachments_data['url'] = $data['attachments'];

            $attachments = $this->create_attachments->handle($attachments_data);

            $post['attachments'] = $attachments->getDataByKey('attachments');
            $response->addData('post', $post);

            if ($attachments->isError()) {
                $attachments->addData('post', $post);

                return $attachments;
            }
        }
        //endregion Create Attachments

        //region Create Tags
        if (isset($data['tags']) &&
            is_array($data['tags'])) {

            $post_id = $post['id'];
            $taggable_type = config('arbitrage.tags.model.taggable_type.post.value');

            $tags = collect($data['tags'])->map(function ($tag) use ($post_id, $taggable_type) {
                $tag['taggable_id'] = $post_id;
                $tag['taggable_type'] = $taggable_type;
                return $tag;
            });

            $post_model->tags()->createMany($tags);

            $response->addData('tags', $tags);
        }
        //endregion Create Tags

        //region Fire event
        event(new UserPostedEvent($post_model));
        //endregion Fire event

        return $response;
    }
}
