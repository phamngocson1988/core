<div class="row">
  <div class="col-md-6">
      <div class="portlet light bordered">
          <div class="portlet-title">
              <div class="caption">
                  <i class="icon-bubble font-green-sharp"></i>
                  <span class="caption-subject font-green-sharp bold uppercase">Checkable Tree</span>
              </div>
          </div>
          <div class="portlet-body">
              <div id="tree_2" class="tree-demo"> </div>
          </div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
      <div class="portlet light bordered">
          <div class="portlet-title">
              <div class="caption">
                  <i class="icon-bubble font-green-sharp"></i>
                  <span class="caption-subject font-green-sharp bold uppercase">Checkable Tree</span>
              </div>
          </div>
          <div class="portlet-body">
              <div id="tree_custom" class="tree-demo"> 
                <ul>
                  <li>Demo 1</li>
                  <li>Demo 1</li>
                  <li>Demo 1
                    <ul>
                      <li>Children 1</li>
                      <li>Children 1</li>
                    </ul>
                  </li>
                </ul>
              </div>
          </div>
      </div>
  </div>
</div>
{registerJs}
{literal}
$('#tree_custom').jstree({"plugins" : [ "checkbox" ]});
{/literal}
{/registerJs}