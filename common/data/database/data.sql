create database IF NOT EXISTS jimei;
use jimei;

-- 图片原始素材表
CREATE TABLE if NOT EXISTS jimei_theme(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  `name` VARCHAR(15) NOT NULL default '' comment '素材名称',
  template_url VARCHAR(255) NOT NULL default '' comment '图片地址',
  source_pic_name VARCHAR(255) NOT NULL default '' comment '原图片名称',
  barcode char(5) NOT NULL default  '' comment '条码识别字符',
  `customer_id` int unsigned NOT NULL default 0 comment'客户id',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (`customer_id`,`barcode`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1;

-- 图案材质关系列表
CREATE TABLE if NOT EXISTS jimei_theme_material(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  `theme_id` int unsigned NOT NULL default 0 comment'图案id',
  `material_id` int unsigned NOT NULL default 0 comment'材质id',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (`theme_id`,`material_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1;

-- 材质
CREATE TABLE if NOT EXISTS jimei_material(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  `name` VARCHAR(15) NOT NULL default '' comment '材质名称',
  barcode char(5) NOT NULL default  '' comment '条码识别字符',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (barcode)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'材质';

-- 配货单
CREATE TABLE if NOT EXISTS jimei_base_list(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  `sn` VARCHAR(15) NOT NULL default '' comment '打印单号',
  `num` int unsigned NOT NULL default  0 comment'订单数量',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  task_status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 1：未完成 2：任务处理锁定中 3: 已完成',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (sn)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'配货单列表';

-- 订单列表
CREATE TABLE if NOT EXISTS jimei_order(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  `order_id` VARCHAR(255) NOT NULL default '' comment'原始订单号',
  `base_id` int unsigned NOT NULL default  0 comment'打印单号',
  print_flag tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：未打印 1：已打印',
  is_refund tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：已退款',
  barcode char(20) NOT NULL default  '' comment '完整二维码识别号 example: HW0010101MW0001',
  brand_id int unsigned NOT NULL default  0 comment'品牌id',
  mobile_id int unsigned NOT NULL default  0 comment'手机型号id',
  customer_id int unsigned NOT NULL default  0 comment'客户id',
  theme_id int unsigned NOT NULL default  0 comment'素材',
  color_id int unsigned NOT NULL default  0 comment'颜色',
  material_id int unsigned NOT NULL default  0 comment'材质',
  source varchar(125) not null default ''comment'订单来源',
  goodsname varchar(255) not null default '' comment '本地商品名称',
  lcmccode varchar(255) not null default '' comment '本地商家编码',
  mccode varchar(255) not null default '' comment '网店商家编码',
  num int unsigned NOT NULL DEFAULT 0 comment'数量',
  suitecode varchar(255) not null default '' comment '套餐编码',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (order_id,base_id, suitecode)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'订单';

-- 机型材质
CREATE TABLE if NOT EXISTS jimei_phone_material_relation(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  mobile_id VARCHAR(15) NOT NULL default '' comment'机型id',
  `material_id` int unsigned NOT NULL default  0 comment'材质id',
  source_pic_name VARCHAR(255) NOT NULL default '' comment '原图片名称',
  `left` decimal(5,2) NOT NULL default  0 comment'左右边距',
  `top` decimal(5,2) NOT NULL default  0 comment'上下边距',
  border_url varchar(255) NOT NULL default '' comment'圆角素材链接',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (mobile_id,material_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'机型材质关系表';

-- 手机品牌
CREATE TABLE if NOT EXISTS jimei_brand(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  `name` VARCHAR(15) NOT NULL default '' comment '品牌名称',
  barcode char(5) NOT NULL default  '' comment '条码识别字符',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (barcode)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'品牌';

-- 手机型号
CREATE TABLE if NOT EXISTS jimei_phone(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  brand_id int unsigned NOT NULL default  0 comment '品牌名称',
  barcode char(5) NOT NULL default  '' comment '条码识别字符',
  modal VARCHAR(15) NOT NULL DEFAULT '' comment'手机型号名称',
  width decimal(5,2) NOT NULL DEFAULT 0 comment'手机宽',
  height decimal(5,2) NOT NULL DEFAULT 0 comment'手机高',
  canvas_type tinyint(1) NOT NULL DEFAULT  1 comment '画布类型 1:普通 2:大画布',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (brand_id,barcode)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'机型';

-- 颜色
CREATE TABLE if NOT EXISTS jimei_color(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  `name` VARCHAR(15) NOT NULL DEFAULT '' comment'名称',
  barcode char(5) NOT NULL default  '' comment '条码识别字符',
  rdg char(6) NOT NULL DEFAULT '000000' comment'色值',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (barcode)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'颜色';

-- 客户
CREATE TABLE if NOT EXISTS jimei_customer(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  `name` VARCHAR(15) NOT NULL DEFAULT '' comment'名称',
  barcode char(5) NOT NULL default  '' comment '条码识别字符',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (barcode)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'客户';

-- 套餐
CREATE TABLE if NOT EXISTS jimei_meal(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'主键',
  barcode char(20) NOT NULL default  '' comment '完整二维码识别号 example: HW0010101MW0001',
  brand_id int unsigned NOT NULL default  0 comment'品牌id',
  mobile_id int unsigned NOT NULL default  0 comment'手机型号id',
  customer_id int unsigned NOT NULL default  0 comment'客户id',
  theme_id int unsigned NOT NULL default  0 comment'素材',
  color_id int unsigned NOT NULL default  0 comment'颜色',
  material_id int unsigned NOT NULL default  0 comment'材质',
  sync_status tinyint unsigned NOT NULL DEFAULT 0 comment'同步状态 0：未同步 1 已同步',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：有效 1：删除',
  unique (brand_id, mobile_id,customer_id,theme_id,color_id,material_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 auto_increment=1 comment'套餐';

-- 管理平台相关记录表
CREATE TABLE IF NOT EXISTS jimei_role(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  `name` char(25) NOT NULL comment'角色名称',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1 comment'管理员角色列表';

create TABLE IF NOT EXISTS jimei_admin(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  role_id int unsigned NOT null default 0 comment'角色',
  `username` char(32) NOT NULL COMMENT '昵称，允许修改',
  `password` char(255) NOT NULL DEFAULT '',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1 comment'管理员列表';

CREATE TABLE IF NOT EXISTS jimei_source(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  parent_id int unsigned NOT NULL DEFAULT 0 comment'父id',
  `name` char(25) NOT NULL comment'资源名称',
  request char(25) NOT NULL comment'对应的控制器方法',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned   NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned   NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1 comment'资源列表';

-- 角色资源关系表
CREATE TABLE IF NOT EXISTS jimei_role_source(
  `id` int unsigned NOT NULL PRIMARY  key AUTO_INCREMENT COMMENT '自增ID，主键',
  role_id int  unsigned NOT NULL comment'角色id',
  source_id int unsigned NOT NULL comment'资源id',
  sort_order int unsigned  NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned  NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned  NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1 comment'资源角色关系表';

CREATE TABLE IF NOT EXISTS jimei_admin_operation(
  id int unsigned NOT NULL PRIMARY KEY auto_increment comment'id',
  user_id int unsigned NOT NULL DEFAULT 0 comment'用户id',
  content VARCHAR(255) NOT NULL DEFAULT '' comment'操作内容说明',
  sort_order int unsigned NOT NULL DEFAULT 500 comment'排序字段',
  create_time bigint unsigned NOT NULL DEFAULT 0 comment'创建时间',
  update_time bigint unsigned NOT NULL DEFAULT 0 comment'修改时间',
  status tinyint unsigned NOT NULL DEFAULT 0 comment'状态 0：1：删除',
  key user_id(user_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 auto_increment=1 comment'管理员操作记录表';