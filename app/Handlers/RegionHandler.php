<?php

namespace App\Handlers;

use App\Models\Region;
use Illuminate\Support\Collection;

class RegionHandler
{
    /**
     * @return Region
     */
    public function findRoot(): Region
    {
        return Region::root();
    }

    /**
     * @return Region
     */
    public function findNodeById(int $id): Region
    {
        return Region::find($id);
    }

    /**
     * @return Region
     */
    public function findNodeBySlug(string $slug): Region
    {
        return Region::where('slug', $slug)->first();
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getFamilyBranch(int $id): Collection
    {
        return Region::whereAncestorOrSelf($id)
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
        return Region::whereIn('slug', $slugs)
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
        return Region::whereIsLeaf()->get();
    }

    public function getFamilyRegionIds(string $slug): array
    {
        $region = Region::where('slug', $slug)->first();
        if ($region) {
            return Region::ancestorsAndSelf($region->id)
                ->filter(function ($value) {
                    return $value->depth > 0;
                })
                ->pluck('id')
                ->toArray();
        }

        return [];
    }
}
