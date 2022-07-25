<?php
/**
 * ProductDetail.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-23 11:33:06
 * @modified   2022-06-23 11:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->description->name ?? '',
            'description' => $this->description->description ?? '',
            'image' => image_resize($this->image),
            'category_id' => $this->category_id ?? null,
            'variables' => $this->decodeVariables($this->variables),
            'skus' => SkuDetail::collection($this->skus)->jsonSerialize(),
        ];
    }


    /**
     * 处理多规格商品数据
     *
     * @param $variables
     * @return array|array[]
     * @throws \Exception
     */
    private function decodeVariables($variables): array
    {
        $lang = current_language_code();
        if (empty($variables)) {
            return [];
        }
        return array_map(function ($item) use ($lang) {
            return [
                'name' => $item['name'][$lang] ?? '',
                'values' => array_map(function ($item) use ($lang) {
                    return [
                        'name' => $item['name'][$lang] ?? '',
                        'image' => image_resize('catalog/' . $item['image']),
                    ];
                }, $item['values']),
            ];
        }, $variables);
    }
}
