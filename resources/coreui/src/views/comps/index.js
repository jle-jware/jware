// if some dynamic components are used frequently, a better way is to register them globally
export default {
  DisplayRow: require('./nested-DisplayRow'),
  Color: require('./td-Color'),
  CreatetimeTd: require('./td-Createtime'),
  CreatetimeTh: require('./th-Createtime'),
  Email: require('./td-Email'),
  IP: require('./td-IP'),
  Opt: require('./td-Opt'),
  ObjectName: require('./td-ObjectName'),
  
  Anchor: require('./td-anchor'),
  Clickable: require('./td-clickableRow'),

  
  FilterTh: require('./th-Filter'),
  FilterDateTh: require('./th-FilterDate'),
  FilterDateRangeTh: require('./th-FilterDateRange'),
  ShowImageTd: require('./td-showImage'),
  ShowBooleanValueTd: require('./td-showBoolean'),
  ShowLinkWebsiteTd: require('./td-showLinkWebsite'),
  showCompanyEditLinkTd: require('./td-showCompanyEditLink'),
  showAccountNameTd: require('./td-showAccountName')
  // [Vue warn]: Do not use built-in or reserved HTML elements as component id: Filter
  // Filter: require('./th-Filter')
}
