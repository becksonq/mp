<?php

namespace app\modules\mailparsing\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\mailparsing\models\Profile;

/**
 * ProfileSearch represents the model behind the search form about `app\modules\mailparsing\models\Profile`.
 */
class ProfileSearch extends Profile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'delivery_digest_at_hour', 'delivery_digest_at_minutes', 'delivery_digest_at'], 'integer'],
            [['firstname', 'lastname', 'image', 'slack_nickname', 'youtrack_nickname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Profile::find();

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
            'user_id' => $this->user_id,
            'delivery_digest_at_hour' => $this->delivery_digest_at_hour,
            'delivery_digest_at_minutes' => $this->delivery_digest_at_minutes,
            'delivery_digest_at' => $this->delivery_digest_at,
        ]);

        $query->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'slack_nickname', $this->slack_nickname])
            ->andFilterWhere(['like', 'youtrack_nickname', $this->youtrack_nickname]);

        return $dataProvider;
    }
}
