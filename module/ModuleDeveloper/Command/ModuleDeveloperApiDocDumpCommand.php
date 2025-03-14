<?php


namespace Module\ModuleDeveloper\Command;


use Illuminate\Console\Command;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\Util\SerializeUtil;
use Module\ModuleDeveloper\Util\ModuleApiDocSwaggerUtil;

class ModuleDeveloperApiDocDumpCommand extends Command
{
    protected $signature = 'ModuleDeveloper:ApiDocDump {file}';
    protected $description = 'ModuleDeveloper:ApiDocDump {file} 导出API文档';

    public function handle()
    {
        $file = trim($this->argument('file'));
        BizException::throwsIf('文件已存在', file_exists($file));
        $ret = ModuleApiDocSwaggerUtil::generateApp();
        BizException::throwsIfResponseError($ret);
        BizException::throwsIf('该模块没有接口文档', empty($ret['data']['json']));
        $content = SerializeUtil::jsonEncode($ret['data']);
        FileUtil::write($file, $content);
        $this->info('success');
    }
}
