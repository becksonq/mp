<?php
/**
 * User: Администратор
 * Date: 20.08.2017
 * Time: 15:19
 */

namespace app\modules\mailparsing\models;

use app\models\tables\Post;
use app\models\tables\Project;
use app\models\tables\User;
use Tests\Behat\Gherkin\ParserExceptionsTest;
use yii;
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD;
use app\models\tables\Profile;

class Parsing
{

    public $mail_array = [ ];
    public $el = [ ];
    public $tmp;
    public $t;
    public $messages;

    /**
     * @return array
     */
    public function getUsersFromTable()
    {
        $users = [ ];

        // Получаем данные из таблицы
        $query = Profile::find()->asArray()->where( [
            'not',
            [ 'firstname' => null ]
        ] )->all();

        // Получаем имя/фамилию из данных
        foreach ( $query as $val ) {
            $users[] = $val['lastname'] . $val['firstname'];
        }

        return $users;
    }

    /**
     * @return array|yii\db\ActiveRecord[]
     */
    public function getProjectFromTable()
    {
        $projects = [ ];

        $query = Project::find()->select( 'name' )->asArray()->all();

        foreach ( $query as $el ) {
            $projects[] = $el['name'];
        }

        return $projects;
    }

    /**
     * Получаем массив страниц html
     *
     * @param $object
     *
     * @return array
     */
    public function getHtmlArray( $object )
    {

        $html_array = [ ];
        $files = [ ];

        foreach ( $object as $key0 => $value ) {

            // Перебираем письма
            foreach ( $value['attachs'] as $key1 => $file ) {
                $html = SHD::str_get_html( $file['file'] );
                foreach ( $html->find( 'style, meta, link, title, comment' ) as $style ) {
                    $style->outertext = '';
                }

                $str = $html->save();
                $html->clear();
                $html = SHD::str_get_html( $str );

//                H::h( $html, 0); //exit;

                if ( count( $html->find( 'table[bgcolor="#e8eaf6"]' ) ) ) {
                    foreach ( $html->find( 'table[bgcolor="#e8eaf6"]' ) as $item ) {
                        foreach ( $item->find( 'tr' ) as $key => $tr ) {
                            if ( !is_object( $tr ) ) {
                                continue;
                            }

                            if ( $t = $tr->find( 'td[class=project-name]' ) ) {
                                // Если нашли название проекта...
                                $this->tmp = $key;

                                foreach ( $t as $k ) {
                                    $tmp_array[$this->tmp]['project'] = trim( $k->innertext );
                                }
                                unset( $k );
                            }
                            else {
                                if ( $dtable = $tr->find( 'table[align=center]' ) ) {
                                    // Если нашли детали...
                                    foreach ( $dtable as $el ) {
                                        $tmp_array1['user'] = trim( $el->find( 'a.user-name-link',
                                            0 )->plaintext );

                                        $tmp_array1['post'] = trim( str_replace( '/\s{2,}/', ' ',
                                            $el->find( 'td.post-text', 0 )->innertext ) );
                                    }
                                    unset( $el );

                                    if ( count( $tmp_array1 ) > 0 ) {
                                        $tmp_array[$this->tmp]['details'][$this->t] = $tmp_array1;
                                        $this->t++;
                                    }
                                    $tmp_array1 = [ ];
                                }
                            }
                        }
                        unset( $tr );
                        $this->t = 0;
                        if ( count( $tmp_array ) > 0 ) {
                            $files[] = $tmp_array;
                            $tmp_array = [ ];
                        }
                    }
                    unset ( $item );
                }
            }
            unset( $file );

            $html_array[$key0]['id'] = $value['id'];
            $html_array[$key0]['time'] = $value['time']; //H::h($html_array[$key0]['time'],3);
            $html_array[$key0]['date'] = $value['date'];
            $html_array[$key0]['to'] = $value['to'];
            $html_array[$key0]['from'] = $value['from'];
            $html_array[$key0]['title'] = $value['title'];
            $html_array[$key0]['attachs'] = $files;
        }
        unset( $value );

        $html->clear();
        return $html_array;
    }

    /**
     * Функция записи данных в базу
     *
     * @param $object
     *
     * @return array
     */
    public function whiteDataToTable( $object )
    {
        $messages = [ ];
        // Получаем пользователей которые уже есть в базе...
        $old_users = $this->getUsersFromTable();

        foreach ( $object as $value ) {
            foreach ( $value['attachs'] as $key => $val1 ) {

                // Обрабатываем проекты...
                foreach ( $val1 as $val2 ) {
                    // Получаем названия проектов из таблицы project
                    $old_project = $this->getProjectFromTable();
                    foreach ( $old_project as &$el ) {
                        $el = str_replace( " ", "", $el );
                    }

                    $tmp_project = str_replace( " ", "", $val2['project'] );

                    // Сравниваем проекты и находим новые...
                    if ( !in_array( $tmp_project, $old_project ) ) {
                        $project = new Project();
                        $project->name = $val2['project'];
                        $project->save();

                        // Добавляем проект в массив...
                        array_push( $old_project, $tmp_project );
                        // Добавляем проект в массив статистики...
                        $this->messages['new-project'][] = $val2['project'];
                    }

                    foreach ( $val2 as $details ) {

                        // Обрабатываем юзеров
                        if ( is_array( $details ) ) {
                            foreach ( $details as $key4 => $val4 ) {

                                // Удаляем пробелы из строки и получаем пользователя из письма для сравнения
                                $user_tmp = str_replace( " ", "", $val4['user'] );
                                $u = explode( ' ', $val4['user'] );

                                // Сравниваем юзеров для нахождения новых
                                if ( !in_array( $user_tmp, $old_users ) ) {
                                    // Если находим нового юзера, то пишем его в таблицу...
                                    $profile = new Profile();
                                    $user = new User();

                                    // Данные для таблицы user
                                    $user->username = $u[0];
                                    $user->auth_key = 'bcF3RTXw0r7j7xKzX6hfULHkLw2TzlX4';
                                    $user->password_hash = '$2y$13$16QcKpUYBRRp6uAXHwxi2ekkrQFtqKcEdpE8ry0dgTyx15pUwzglq';
                                    $user->email = $u[0] . '@gmail.com';
                                    $user->created_at = time();
                                    $user->updated_at = time();
                                    $user->save();

                                    // Данные для таблицы profile
                                    $profile->firstname = $u[1];
                                    $profile->lastname = $u[0];
                                    $profile->user_id = $user->id;
                                    $profile->save();

                                    // Сохраняем пост нового юзера в таблицу post
                                    $post = new Post();
                                    $post->body = $val4['post'];
                                    $post->author_id = $user->id;
                                    // Запрос в project для id проекта...
                                    $query = Project::find()->select( 'id' )->where( [ 'name' => $val2['project'] ] )->one();
                                    $post->project_id = $query->id;
                                    $post->created_at = time();
                                    $post->updated_at = time();
                                    $post->save();

                                    // Добавляем пользователя в массив...
                                    array_push( $old_users, $user_tmp );
                                    $this->messages['new-user'][] = $val4['user'];
                                }
                                else {

                                    // Если новых юзеров нет, пишем посты старых в таблицу post...
                                    $post = new Post();
                                    $post->body = $val4['post'];

                                    // Узнаем id юзера...
                                    $pr = Profile::find()->select( 'user_id' )->where( [ 'firstname' => $u[1] ] )->one();
                                    $post->author_id = $pr->user_id;
                                    // Запрос в project для id проекта...
                                    $project = Project::find()->select( 'id' )->where( [ 'name' => $val2['project'] ] )->one();
                                    $post->project_id = $project->id;

                                    $post->created_at = time();
                                    $post->updated_at = time();
                                    $post->save();

                                    $this->messages['posts'][] = $key4;
                                }
                            }
                            unset( $val4 );
                        }
                    }
                    unset( $details );
                }
                unset( $val2 );
            }
            unset( $val );
        }
        unset( $value );

        return $this->messages;
    }

}