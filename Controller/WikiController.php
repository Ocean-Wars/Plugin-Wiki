<?phpclass WikiController extends AppController{    public function index()    {        $this->set('title_for_layout', 'Wiki');        $this->loadModel('Wiki.Wcategories');        $wcategories = $this->Wcategories->find('all');        $this->set(compact("wcategories"));    }    public function getCategorie($id)    {        $this->loadModel('Wiki.Wcategories');        $wcategorie = $this->Wcategories->getCategoriesBySearch('name', $id);        echo $wcategorie;    }    function admin_categories()    {        if ($this->isConnected AND $this->User->isAdmin()) {            $this->set('title_for_layout', $this->Lang->get('WIKI__TITLE_CATE_ADMIN'));            $this->loadModel('Wiki.Wcategories');            $wcategories = $this->Wcategories->find('all');            $this->set(compact("wcategories"));            $this->layout = 'admin';        } else            throw new ForbiddenException();    }    function admin_registre()    {        if ($this->isConnected AND $this->User->isAdmin()) {            $this->set('title_for_layout', $this->Lang->get('WIKI__TITLE_REGISTRE_ADMIN'));            $this->loadModel('Wiki.Wcategories');            $wcategories = $this->Wcategories->find('all');            $this->set(compact("wcategories"));            $this->loadModel('Wiki.Wregistre');            $wregistres = $this->Wregistre->find('all');            $this->set(compact("wregistres"));            $this->layout = 'admin';        } else            throw new ForbiddenException();    }    function load($id)    {        $this->layout = null;        $this->loadModel('Wiki.Wregistre');        $wregistres = $this->Wregistre->find('all', array('conditions' => array("Wregistre.cate_id =" => $id)));        $this->set(compact('wregistres'));        $this->render('load');    }    function admin_edit_categorie($id)    {        if ($this->isConnected AND $this->User->isAdmin()) {            $this->loadModel('Wiki.Wcategories');            $wcategorie = $this->Wcategories->getAllFromCategories($id, 'id');            if (!empty($wcategorie)) {                $this->set('title_for_layout', $this->Lang->get('WIKI__TITLE_CATE_ADMIN'));                $this->set(compact('wcategorie'));                $this->set(compact('id'));                $this->layout = 'admin';            } else {                throw new ForbiddenException();            }        } else            throw new ForbiddenException();    }    function admin_add_categorie()    {        $this->layout = 'admin';        $this->autoRender = false;        if ($this->isConnected && $this->User->isAdmin()) {            if ($this->request->is('post')) {                $this->loadModel('Wiki.Wcategories');                $nameCate = $this->request->data['name_cate'];                $miniatureCate = $this->request->data['miniature_cate'];                $wcategories = $this->Wcategories->getCategoriesBySearch('name', $nameCate);                if (empty($nameCate)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_CATE_NAME'))));                    return;                } elseif (!empty($wcategories)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__ALREADY_CATE_CREATE'))));                    return;                } elseif (empty($miniatureCate)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_MINIATURE'))));                    return;                } elseif (!filter_var($miniatureCate, FILTER_VALIDATE_URL)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__INVALIDE_URL'))));                    return;                } else {                    $this->Wcategories->set(array(                        'name' => $nameCate,                        'miniature' => $miniatureCate,                    ));                    $this->Wcategories->save();                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('WIKI__CATE_CREATE'))));                    return;                }            }        } else            throw new ForbiddenException();    }    function admin_update_categorie()    {        $this->layout = 'admin';        $this->autoRender = false;        if ($this->isConnected && $this->User->isAdmin()) {            if ($this->request->is('post')) {                $this->loadModel('Wiki.Wcategories');                $idGetCate = $this->request->data['id'];                $nameCate = $this->request->data['name_cate'];                $miniatureCate = $this->request->data['miniature_cate'];                $wcategoriesCheck = $this->Wcategories->getCategoriesBySearch('name', $nameCate);                if (empty($nameCate)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_CATE_NAME'))));                    return;                } elseif (!empty($wcategoriesCheck) AND $wcategoriesCheck !== $nameCate) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__ALREADY_CATE_CREATE'))));                    return;                } elseif (empty($miniatureCate)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_MINIATURE'))));                    return;                } elseif (!filter_var($miniatureCate, FILTER_VALIDATE_URL)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__INVALIDE_URL'))));                    return;                } else {                    $this->Wcategories->read(null, $idGetCate);                    $this->Wcategories->set(array(                        'name' => $nameCate,                        'miniature' => $miniatureCate,                    ));                    $this->Wcategories->save();                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('WIKI__CATE_UPDATE'))));                    return;                }            }        } else            throw new ForbiddenException();    }    function admin_delete_categorie()    {        $this->layout = 'admin';        $this->autoRender = false;        if ($this->isConnected && $this->User->isAdmin()) {            if (!empty($this->request->data)) {                if (!empty($this->request->data['id_categorie_delete'])) {                    $this->loadModel('Wiki.Wcategories');                    $this->Wcategories->delete($this->request->data['id_categorie_delete']);                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('WIKI__DELETE_ACTION'))));                    return;                }            }        } else            throw new ForbiddenException();    }    function admin_edit_registre($id)    {        if ($this->isConnected AND $this->User->isAdmin()) {            $this->loadModel('Wiki.Wregistre');            $wregistre = $this->Wregistre->find('first', array('conditions' => array("Wregistre.id =" => $id)));            if (!empty($wregistre)) {                $this->set('title_for_layout', $this->Lang->get('WIKI__TITLE_REGISTRE_ADMIN'));                $this->set(compact('wregistre'));                $this->loadModel('Wiki.Wcategories');                $wcategories = $this->Wcategories->find('all', array('conditions' => array("Wcategories.id !=" => $wregistre['Wregistre']['cate_id'])));                $this->set(compact("wcategories"));                $this->set(compact('id'));                $this->layout = 'admin';            } else {                throw new ForbiddenException();            }        } else            throw new ForbiddenException();    }    function admin_add_registre()    {        $this->layout = 'admin';        $this->autoRender = false;        if ($this->isConnected && $this->User->isAdmin()) {            if ($this->request->is('post')) {                $this->loadModel('Wiki.Wregistre');                $nameRegistre = $this->request->data['name_registre'];                $miniatureRegistre = $this->request->data['miniature_registre'];                $cateRegistre = $this->request->data['cate_registre'];                $descRegistre = $this->request->data['desc_registre'];                $wregistreCheck = $this->Wregistre->getWregistreBySearch('name', $nameRegistre);                if (empty($nameRegistre)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_REGISTRE_NAME'))));                    return;                } elseif (!empty($wregistreCheck)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__ALREADY_REGISTRE_CREATE'))));                    return;                } elseif (empty($miniatureRegistre)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_MINIATURE'))));                    return;                } elseif (!filter_var($miniatureRegistre, FILTER_VALIDATE_URL)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__INVALIDE_URL'))));                    return;                } elseif (empty($descRegistre)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__LENGTH_DESC_REGISTRE'))));                    return;                } else {                    $this->Wregistre->set(array(                        'name' => $nameRegistre,                        'miniature' => $miniatureRegistre,                        'cate_id' => $cateRegistre,                        'desc' => $descRegistre                    ));                    $this->Wregistre->save();                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('WIKI__CREATE_REGISTRE'))));                    return;                }            }        } else            throw new ForbiddenException();    }    function admin_update_registre()    {        $this->layout = 'admin';        $this->autoRender = false;        if ($this->isConnected && $this->User->isAdmin()) {            if ($this->request->is('post')) {                $this->loadModel('Wiki.Wregistre');                $idGetRegistre = $this->request->data['id'];                $nameRegistre = $this->request->data['name_registre'];                $miniatureRegistre = $this->request->data['miniature_registre'];                $cateRegistre = $this->request->data['cate_registre'];                $descRegistre = $this->request->data['desc_registre'];                $wregistreCheck = $this->Wregistre->getWregistreBySearch('name', $nameRegistre);                if (empty($nameRegistre)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_REGISTRE_NAME'))));                    return;                } elseif (!empty($wregistreCheck) AND $wregistreCheck !== $nameRegistre) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__ALREADY_REGISTRE_CREATE'))));                    return;                } elseif (empty($miniatureRegistre)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__EMPTY_MINIATURE'))));                    return;                } elseif (!filter_var($miniatureRegistre, FILTER_VALIDATE_URL)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__INVALIDE_URL'))));                    return;                } elseif (empty($descRegistre)) {                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('WIKI__LENGTH_DESC_REGISTRE'))));                    return;                } else {                    $this->Wregistre->read(null, $idGetRegistre);                    $this->Wregistre->set(array(                        'name' => $nameRegistre,                        'miniature' => $miniatureRegistre,                        'cate_id' => $cateRegistre,                        'desc' => $descRegistre                    ));                    $this->Wregistre->save();                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('WIKI__REGISTRE_UPDATE'))));                    return;                }            }        } else            throw new ForbiddenException();    }    function admin_delete_registre()    {        $this->layout = 'admin';        $this->autoRender = false;        if ($this->isConnected && $this->User->isAdmin()) {            if (!empty($this->request->data)) {                if (!empty($this->request->data['id_registre_delete'])) {                    $this->loadModel('Wiki.Wregistre');                    $this->Wregistre->delete($this->request->data['id_registre_delete']);                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('WIKI__DELETE_ACTION'))));                    return;                }            }        } else            throw new ForbiddenException();    }}