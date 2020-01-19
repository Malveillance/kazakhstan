<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "machines".
 *
 * @property int $id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $draft
 * @property string $name
 * @property string $rev
 * @property string $manufacturer
 * @property int $manufacturer_country
 * @property string $manufacturer_url
 * @property string $agent
 * @property string $agent_url
 * @property int $process
 * @property int|null $build_platform_d
 * @property int|null $build_platform_x
 * @property int|null $build_platform_y
 * @property int|null $build_platform_z
 * @property int $build_heat
 * @property int|null $build_heat_t_max
 * @property string $build_heat_desc
 * @property int $laser_type
 * @property int $laser_count
 * @property int|null $laser1_power
 * @property int|null $laser1_d
 * @property int|null $laser1_wl
 * @property int|null $laser2_power
 * @property int|null $laser2_d
 * @property int|null $laser2_wl
 * @property int|null $laser3_power
 * @property int|null $laser3_d
 * @property int|null $laser3_wl
 * @property int|null $laser4_power
 * @property int|null $laser4_d
 * @property int|null $laser4_wl
 * @property int|null $layer_thickness_min
 * @property int|null $layer_thickness_max
 * @property int|null $scan_speed_max
 * @property int|null $performance
 * @property int|null $dimension_l
 * @property int|null $dimension_w
 * @property int|null $dimension_h
 * @property int|null $weight
 * @property int|null $dimension_inst_l
 * @property int|null $dimension_inst_w
 * @property int|null $dimension_inst_h
 * @property int|null $dimension_tran_l
 * @property int|null $dimension_tran_w
 * @property int|null $dimension_tran_h
 * @property string $mains_connection
 * @property int|null $voltage
 * @property int|null $frequency
 * @property int|null $power_cons
 * @property int|null $mains_fuse
 * @property string $gas_type
 * @property string $gas_purity
 * @property int|null $gas_cons_min
 * @property int|null $gas_cons_purge
 * @property int|null $gas_cons_build
 * @property int|null $gas_pressure_min
 * @property string $connection_type
 * @property string $cnc_system
 * @property string $images
 */
class Machine extends \yii\db\ActiveRecord
{
    const MAX_IMAGE_SIZE = 8;

    public $select;

    public $upload;

    public $raw_gas_type;
    public $raw_images;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'machines';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['upload', 'image', 'extensions' => 'png, jpg, jpeg', 'maxSize' => self::MAX_IMAGE_SIZE * 1048576, 'tooBig' => 'Файл "{file}" слишком большой. Размер не должен превышать ' . self::MAX_IMAGE_SIZE . ' Мб.'],
            [['name', 'rev', 'manufacturer', 'agent', 'mains_connection', 'gas_purity', 'connection_type', 'cnc_system'], 'string', 'max' => 255],
            [['build_heat_desc', 'gas_type', 'images'], 'string'],
            [['manufacturer_url', 'agent_url'], 'url', 'defaultScheme' => 'http'],
            [['created_by', 'process', 'manufacturer_country', 'build_platform_d', 'build_platform_x', 'build_platform_y', 'build_platform_z',
                'build_heat_t_max', 'laser_type', 'laser1_power', 'laser1_d', 'laser2_power', 'laser2_d', 'laser3_power', 'laser3_d',
                'laser4_power', 'laser4_d', 'layer_thickness_min', 'layer_thickness_max', 'scan_speed_max',
                'dimension_l', 'dimension_w', 'dimension_h', 'weight', 'dimension_inst_l', 'dimension_inst_w', 'dimension_inst_h',
                'dimension_tran_l', 'dimension_tran_w', 'dimension_tran_h', 'voltage', 'frequency', 'power_cons', 'mains_fuse',
                'gas_cons_min', 'gas_cons_purge', 'gas_cons_build', 'gas_pressure_min'], 'integer'],
            [['laser1_wl', 'laser2_wl', 'laser3_wl', 'laser4_wl', 'performance'], 'number'],
            ['laser_count', 'integer', 'min' => 1, 'max' => 4],
            [['draft', 'build_heat'], 'boolean'],
            [['raw_gas_type', 'raw_images'], 'each', 'rule' => ['string']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->select = json_decode(file_get_contents('../static/machine_ru.json'));

        $this->laser_count = 1;
        $this->raw_gas_type = ['0'];

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        // Setup 'created_by'.
        $this->created_by = Yii::$app->user->id;

        // Setup 'gas_type'.
        $this->gas_type = empty($this->raw_gas_type) ? '' : json_encode($this->raw_gas_type);

        // Setup 'images'.
        if (!empty($this->raw_images)) {
            foreach ($this->raw_images as &$filename) $filename = basename(base64_decode($filename));
            $this->images = json_encode($this->raw_images);
        } else {
            $this->images = '';
        }

        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        // Setup 'build_heat_t_max' and 'build_heat_desc'.
        if (!$this->build_heat) {
            $this->build_heat_t_max = null;
            $this->build_heat_desc = '';
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function findOne($condition)
    {
        $query = static::findByCondition($condition)->one();

        // Setup 'raw_gas_type'.
        $query->raw_gas_type = json_decode($query->gas_type, true);

        // Setup 'raw_images'.
        $query->raw_images = json_decode($query->images, true);

        return $query;
    }

    public static function getImageInfo($path)
    {
        clearstatcache();

        $size = filesize($path);
        $dimensions = getimagesize($path);

        $i = min(floor(log($size, 1024)), 2);

        $units = [
            Yii::t('unit', 'байт'),
            Yii::t('unit', 'КБ'),
            Yii::t('unit', 'МБ'),
        ];

        $info[] = Yii::t('app', 'Разрешение: {w} х {h}', ['w' => $dimensions[0], 'h' => $dimensions[1]]);
        $info[] = Yii::t('app', 'Размер: {s} {u}', ['s' => round($size / pow(1024, $i), ($i < 2) ? 0 : 1), 'u' => $units[$i]]);

        return implode(PHP_EOL, $info);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'draft' => Yii::t('app', 'Черновик'),
            'name' => Yii::t('app', 'Модель'),
            'rev' => Yii::t('app', 'Rev.'),
            'manufacturer' => Yii::t('app', 'Производитель'),
            'manufacturer_country' => Yii::t('app', 'Страна'),
            'manufacturer_url' => Yii::t('app', 'Сайт производителя'),
            'agent' => Yii::t('app', 'Представитель в России'),
            'agent_url' => Yii::t('app', 'Сайт представителя'),
            'process' => Yii::t('app', 'Процесс'),
            'build_platform_d' => Yii::t('app', 'Диаметр'),
            'build_platform_x' => Yii::t('app', 'Длина'),
            'build_platform_y' => Yii::t('app', 'Ширина'),
            'build_platform_z' => Yii::t('app', 'Высота'),
            'build_heat' => Yii::t('app', 'Подогрев зоны построения'),
            'build_heat_t_max' => Yii::t('app', 'Макс. температура'),
            'build_heat_desc' => Yii::t('app', 'Описание системы подогрева'),
            'laser_type' => Yii::t('app', 'Тип лазера'),
            'laser_count' => Yii::t('app', 'Количество лазеров'),
            'laser1_power' => Yii::t('app', 'Мощность'),
            'laser1_d' => Yii::t('app', 'Фокусный диаметр'),
            'laser1_wl' => Yii::t('app', 'Длина волны'),
            'laser2_power' => Yii::t('app', 'Мощность'),
            'laser2_d' => Yii::t('app', 'Фокусный диаметр'),
            'laser2_wl' => Yii::t('app', 'Длина волны'),
            'laser3_power' => Yii::t('app', 'Мощность'),
            'laser3_d' => Yii::t('app', 'Фокусный диаметр'),
            'laser3_wl' => Yii::t('app', 'Длина волны'),
            'laser4_power' => Yii::t('app', 'Мощность'),
            'laser4_d' => Yii::t('app', 'Фокусный диаметр'),
            'laser4_wl' => Yii::t('app', 'Длина волны'),
            'layer_thickness_min' => Yii::t('app', 'Мин. толщина слоя'),
            'layer_thickness_max' => Yii::t('app', 'Макс. толщина слоя'),
            'scan_speed_max' => Yii::t('app', 'Макс. скорость сканирования'),
            'performance' => Yii::t('app', 'Производительность процесса'),
            'dimension_l' => Yii::t('app', 'Длина'),
            'dimension_w' => Yii::t('app', 'Ширина'),
            'dimension_h' => Yii::t('app', 'Высота'),
            'weight' => Yii::t('app', 'Масса'),
            'dimension_inst_l' => Yii::t('app', 'Длина'),
            'dimension_inst_w' => Yii::t('app', 'Ширина'),
            'dimension_inst_h' => Yii::t('app', 'Высота'),
            'dimension_tran_l' => Yii::t('app', 'Длина'),
            'dimension_tran_w' => Yii::t('app', 'Ширина'),
            'dimension_tran_h' => Yii::t('app', 'Высота'),
            'mains_connection' => Yii::t('app', 'Сеть'),
            'voltage' => Yii::t('app', 'Номинальное напряжение'),
            'frequency' => Yii::t('app', 'Частота'),
            'power_cons' => Yii::t('app', 'Потребляемая мощность'),
            'mains_fuse' => Yii::t('app', 'Сетевой предохранитель'),
            'raw_gas_type' => Yii::t('app', 'Используемый газ'),
            'gas_purity' => Yii::t('app', 'Степень чистоты'),
            'gas_cons_min' => Yii::t('app', 'Мин. расход на один рабочий цикл'),
            'gas_cons_purge' => Yii::t('app', 'Расход во время продувки'),
            'gas_cons_build' => Yii::t('app', 'Расход во время построения'),
            'gas_pressure_min' => Yii::t('app', 'Мин. давление на входе'),
            'connection_type' => Yii::t('app', 'Интерфейс подключения'),
            'cnc_system' => Yii::t('app', 'Система ЧПУ'),
            'upload' => Yii::t('app', 'Выбрать файл'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints()
    {
        return [
            'build_platform_d' => Yii::t('unit', 'мм'),
            'build_platform_x' => Yii::t('unit', 'мм'),
            'build_platform_y' => Yii::t('unit', 'мм'),
            'build_platform_z' => Yii::t('unit', 'мм'),
            'build_heat_t_max' => Yii::t('unit', '°C'),
            'laser1_power' => Yii::t('unit', 'Вт'),
            'laser1_d' => Yii::t('unit', 'мкм'),
            'laser1_wl' => Yii::t('unit', 'мкм'),
            'laser2_power' => Yii::t('unit', 'Вт'),
            'laser2_d' => Yii::t('unit', 'мкм'),
            'laser2_wl' => Yii::t('unit', 'мкм'),
            'laser3_power' => Yii::t('unit', 'Вт'),
            'laser3_d' => Yii::t('unit', 'мкм'),
            'laser3_wl' => Yii::t('unit', 'мкм'),
            'laser4_power' => Yii::t('unit', 'Вт'),
            'laser4_d' => Yii::t('unit', 'мкм'),
            'laser4_wl' => Yii::t('unit', 'мкм'),
            'layer_thickness_min' => Yii::t('unit', 'мкм'),
            'layer_thickness_max' => Yii::t('unit', 'мкм'),
            'scan_speed_max' => Yii::t('unit', 'м/с'),
            'performance' => Yii::t('unit', 'см³/ч'),
            'dimension_l' => Yii::t('unit', 'мм'),
            'dimension_w' => Yii::t('unit', 'мм'),
            'dimension_h' => Yii::t('unit', 'мм'),
            'weight' => Yii::t('unit', 'кг'),
            'dimension_inst_l' => Yii::t('unit', 'мм'),
            'dimension_inst_w' => Yii::t('unit', 'мм'),
            'dimension_inst_h' => Yii::t('unit', 'мм'),
            'dimension_tran_l' => Yii::t('unit', 'мм'),
            'dimension_tran_w' => Yii::t('unit', 'мм'),
            'dimension_tran_h' => Yii::t('unit', 'мм'),
            'voltage' => Yii::t('unit', 'В'),
            'frequency' => Yii::t('unit', 'Гц'),
            'power_cons' => Yii::t('unit', 'Вт'),
            'mains_fuse' => Yii::t('unit', 'А'),
            'gas_cons_min' => Yii::t('unit', 'л'),
            'gas_cons_purge' => Yii::t('unit', 'л/мин'),
            'gas_cons_build' => Yii::t('unit', 'л/мин'),
            'gas_pressure_min' => Yii::t('unit', 'бар'),
        ];
    }
}
