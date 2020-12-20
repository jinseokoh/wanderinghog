<?php

namespace App\Handlers;

use App\Models\Question;
use Illuminate\Support\Collection;

class QuestionHandler
{
    /**
     * @return Question
     */
    public function findRoot(): Question
    {
        return Question::root();
    }

    /**
     * @return Question
     */
    public function findNodeById(int $id): Question
    {
        return Question::find($id);
    }

    /**
     * @return Question
     */
    public function findNodeBySlug(string $slug): Question
    {
        return Question::where('slug', $slug)->first();
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getFamilyBranch(int $id): Collection
    {
        return Question::whereAncestorOrSelf($id)
            ->whereNotNull('parent_id')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getTree(): Collection
    {
        $root = $this->findRoot();

        return $root->descendants->toTree();
    }

    /**
     * @return Collection
     */
    public function getNodes(): Collection
    {
        $root = $this->findRoot();

        return $root->descendants;
    }

    /**
     * @param array $slugs
     * @return array
     */
    public function getFamilyBranchIds(array $slugs): array
    {
        $nodeIds = $this->getIds($slugs);

        $items = new Collection();
        foreach ($nodeIds as $nodeId) {
            $ids = $this->getFamilyBranch($nodeId)->pluck('id');
            $items = $items->merge($ids);
        }

        return $items->unique()->toArray();
    }

    /**
     * @param array $slugs
     * @return array
     */
    public function getFamilyBranchSlugs(array $slugs): array
    {
        $nodeIds = $this->getIds($slugs);

        $items = new Collection();
        foreach ($nodeIds as $nodeId) {
            $slugSet = $this->getFamilyBranch($nodeId)->pluck('slug');
            $items = $items->merge($slugSet);
        }

        return $items->unique()->toArray();
    }

    /**
     * @param array $slugs
     * @return array
     */
    public function getIds(array $slugs): array
    {
        return Question::whereIn('slug', $slugs)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * @return Collection
     */
    public function getLeafNodeOf(string $slug): Collection
    {
        $node = $this->findNodeBySlug($slug);

        return $node->descendants()
            ->whereRaw('_lft = _rgt - 1')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getLeafNodes(): Collection
    {
        return Question::whereIsLeaf()->get();
    }
}
