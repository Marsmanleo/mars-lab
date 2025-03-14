<?php


namespace Module\Cms\Api\Controller;


use Carbon\Carbon;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\FormatUtil;
use Module\Cms\Field\CmsField;
use Module\Cms\Util\CmsContentUtil;

/**
 * @Api 通用CMS
 */
class FormController extends BaseCatController
{
    /**
     * @Api 表单-获取详情
     * @ApiBodyParam cat string 栏目标识（ID、名称）
     */
    public function index()
    {
        $input = InputPackage::buildFromInput();
        $catId = $input->getTrimString('cat');
        BizException::throwsIfEmpty('分类为空', $catId);
        $data = parent::setup($catId);
        $viewData = $data;
        return Response::generateSuccessData($viewData);
    }

    /**
     * @Api 表单-内容提交
     * @ApiBodyParam cat string 栏目标识（ID、名称）
     * @ApiBodyParam content string 内容
     * @ApiBodyParam xxx string 其他信息
     */
    public function submit()
    {
        $input = InputPackage::buildFromInput();
        $catId = $input->getTrimString('cat');
        $data = parent::setup($catId);
        $input = InputPackage::buildFromInput();
        $submitData = [];
        $submitData['content'] = $input->getRichContent('content');
        $customFields = isset($data['cat']['_model']['_customFields']) ? $data['cat']['_model']['_customFields'] : [];
        if (!empty($customFields)) {
            foreach ($customFields as $customField) {
                $f = CmsField::getByNameOrFail($customField['fieldType']);
                $submitData[$customField['name']] = $f->prepareInputOrFail($customField, $input);
                if (!empty($customField['isRequired'])) {
                    if (empty($submitData[$customField['name']])) {
                        return Response::generateError($customField['title'] . '不能为空');
                    }
                }
                $ret = $f->validateInputValue($customField, $submitData[$customField['name']], $submitData);
                if (Response::isError($ret)) {
                    return Response::generateError($ret['message']);
                }
                $submitData[$customField['name']] = $f->serializeValue($submitData[$customField['name']], $submitData);
            }
        }
//        if (empty($submitData['content'])) {
//            if (Request::isAjax()) {
//                return Response::generateError('内容为空');
//            }
//            return Response::send(-1, '内容为空', null, Request::headerReferer());
//        }
        if (empty($submitData['email'])) {
            if (Request::isAjax()) {
                return Response::generateError('邮箱为空');
            }
            return Response::send(-1, '邮箱为空', null, Request::headerReferer());
        } else {
            if (!FormatUtil::isEmail($submitData['email'])) {
                if (Request::isAjax()) {
                    return Response::generateError('邮箱格式不正确');
                }
                return Response::send(-1, '邮箱格式不正确', null, Request::headerReferer());
            }
        }
        $submitDataPrimary = [];
        $submitDataPrimary['catId'] = $data['cat']['id'];
        $submitDataPrimary['postTime'] = Carbon::now();
        CmsContentUtil::insert($data['cat']['_model'], $submitDataPrimary, $submitData);
        if (Request::isAjax()) {
            return Response::generate(0, '提交成功', null, '[reload]');
        }
        return Response::send(0, '提交成功', null, Request::headerReferer());
    }
}
