<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machine;

/**
 * MachineSearch represents the model behind the search form of `app\models\Machine`.
 */
class MachineSearch extends Machine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'draft', 'manufacturer_country', 'process', 'build_platform_d', 'build_platform_x', 'build_platform_y', 'build_platform_z', 'build_heat', 'build_heat_t_max', 'laser_type', 'laser_count', 'laser1_power', 'laser1_d', 'laser2_power', 'laser2_d', 'layer_thickness_min', 'layer_thickness_max', 'scan_speed_max', 'dimension_l', 'dimension_w', 'dimension_h', 'weight', 'dimension_inst_l', 'dimension_inst_w', 'dimension_inst_h', 'dimension_tran_l', 'dimension_tran_w', 'dimension_tran_h', 'gas_type', 'gas_consumption', 'gas_pressure'], 'integer'],
            [['model', 'manufacturer', 'manufacturer_url', 'agent', 'agent_url', 'build_heat_desc', 'energy_supply', 'cnc_system'], 'safe'],
            [['laser1_wl', 'laser2_wl'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Machine::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'draft' => $this->draft,
            'manufacturer_country' => $this->manufacturer_country,
            'process' => $this->process,
            'build_platform_d' => $this->build_platform_d,
            'build_platform_x' => $this->build_platform_x,
            'build_platform_y' => $this->build_platform_y,
            'build_platform_z' => $this->build_platform_z,
            'build_heat' => $this->build_heat,
            'build_heat_t_max' => $this->build_heat_t_max,
            'laser_type' => $this->laser_type,
            'laser_count' => $this->laser_count,
            'laser1_power' => $this->laser1_power,
            'laser1_d' => $this->laser1_d,
            'laser1_wl' => $this->laser1_wl,
            'laser2_power' => $this->laser2_power,
            'laser2_d' => $this->laser2_d,
            'laser2_wl' => $this->laser2_wl,
            'layer_thickness_min' => $this->layer_thickness_min,
            'layer_thickness_max' => $this->layer_thickness_max,
            'scan_speed_max' => $this->scan_speed_max,
            'dimension_l' => $this->dimension_l,
            'dimension_w' => $this->dimension_w,
            'dimension_h' => $this->dimension_h,
            'weight' => $this->weight,
            'dimension_inst_l' => $this->dimension_inst_l,
            'dimension_inst_w' => $this->dimension_inst_w,
            'dimension_inst_h' => $this->dimension_inst_h,
            'dimension_tran_l' => $this->dimension_tran_l,
            'dimension_tran_w' => $this->dimension_tran_w,
            'dimension_tran_h' => $this->dimension_tran_h,
            'gas_type' => $this->gas_type,
            'gas_consumption' => $this->gas_consumption,
            'gas_pressure' => $this->gas_pressure,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'manufacturer', $this->manufacturer])
            ->andFilterWhere(['like', 'manufacturer_url', $this->manufacturer_url])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'agent_url', $this->agent_url])
            ->andFilterWhere(['like', 'build_heat_desc', $this->build_heat_desc])
            ->andFilterWhere(['like', 'energy_supply', $this->energy_supply])
            ->andFilterWhere(['like', 'cnc_system', $this->cnc_system]);

        return $dataProvider;
    }
}
