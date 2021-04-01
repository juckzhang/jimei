# 接口文档

### 品牌列表
- url: /api/brand-list
- 请求参数:page: 当前页面 count: 每页返回数量

### 机型列表
- url: /api/phone-list
- 请求参数: brand_id: 品牌id / page: 当前页面 / count: 每页返回数量

### 材质列表
- url: /api/material-list
- 请求参数:page: 当前页面 count: 每页返回数量

### 颜色列表
- url: /api/color-list
- 请求参数:page: 当前页面 count: 每页返回数量

### 客户列表
- url: /api/customer-list
- 请求参数:page: 当前页面 count: 每页返回数量

### 素材列表/素材图片链接
- url: /api/theme-list
- 请求参数:customer_id: 客户id page: 当前页面 count: 每页返回数量 update_time: 更新时间 >= 参数时间 默认0

### 机型材质关系信息
- url: /api/relation
- 请求参数:mobile_id: 机型id  material_id:材质id

### 白板图片链接列表
- url: /api/relation-list
- 请求参数:page: 当前页面 count: 每页返回数量 update_time: 更新时间 >= 参数时间 默认0

### 订单列表
- url: /api/list-base
- 请求参数: page: 当前页面 count: 每页返回数量 base_id: 配货单id

### 侧边素材列表/侧边素材图片链接
- url: /api/theme-list
- 请求参数:customer_id: 客户id page: 当前页面 count: 每页返回数量 update_time: 更新时间 >= 参数时间 默认0
