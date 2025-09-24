import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';

const Sidebar = ({ isCollapsed: externalCollapsed, onToggle, mobileMenuOpen, onMobileMenuToggle }) => {
  const [isCollapsed, setIsCollapsed] = useState(externalCollapsed || false);
  const location = useLocation();

  useEffect(() => {
    if (externalCollapsed !== undefined) {
      setIsCollapsed(externalCollapsed);
    }
  }, [externalCollapsed]);

  const menuItems = [
    {
      path: '/',
      name: 'LangGraph 演示',
      icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
      ),
      description: '基础工作流演示'
    },
    {
      path: '/article',
      name: '文章工作流',
      icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      ),
      description: '文章生成与处理'
    },
    {
      path: '/model-testing',
      name: '模型测试',
      icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M12 21v-1m0-16a9 9 0 110 18 9 9 0 010-18z" />
        </svg>
      ),
      description: 'AI 模型性能测试'
    },
    {
      path: '/multi-agent',
      name: '多智能体',
      icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      ),
      description: '协作智能体系统'
    },
    {
      path: '/chat',
      name: '智能对话',
      icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
      ),
      description: 'AI 助手对话'
    },
    {
      path: '/workflow-validation',
      name: '工作流验证',
      icon: (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      ),
      description: '流程验证与测试'
    }
  ];

  const toggleSidebar = () => {
    const newCollapsed = !isCollapsed;
    setIsCollapsed(newCollapsed);
    if (onToggle) {
      onToggle(newCollapsed);
    }
  };

  return (
    <>
      {/* Mobile overlay */}
      {mobileMenuOpen && (
        <div 
          className="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 sm:hidden"
          onClick={() => onMobileMenuToggle(false)}
        />
      )}
      
      {/* Sidebar */}
      <div className={`
        ${isCollapsed ? 'w-12 sm:w-16 flex' : 'w-64 sm:w-72 flex'} 
        ${mobileMenuOpen ? 'fixed left-0 top-0 z-50 sm:relative' : 'hidden sm:flex'}
        glass-effect border-r border-white/20 flex-col transition-all duration-500 ease-spring shadow-strong backdrop-blur-xl relative overflow-hidden max-h-screen
      `}>
      {/* Background decoration */}
      <div className="absolute inset-0 bg-gradient-to-b from-blue-50/20 via-white/10 to-gray-50/20 pointer-events-none"></div>
      <div className="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-blue-100/30 to-transparent pointer-events-none"></div>
      
      {/* Header */}
      <div className="relative p-4 sm:p-6 border-b border-white/10">
        <div className="flex items-center justify-between">
          {!isCollapsed && (
            <div className="flex items-center space-x-2 sm:space-x-4 min-w-0 flex-1">
              <div className="relative flex-shrink-0">
                <div className="w-8 h-8 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 via-blue-600 to-gray-500 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-medium">
                  <svg className="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <div className="absolute -top-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 bg-green-400 border-2 border-white rounded-full"></div>
              </div>
              <div className="min-w-0">
                <h2 className="text-base sm:text-xl font-bold text-gray-900 tracking-tight truncate">LangGraph</h2>
                <p className="text-xs sm:text-sm text-gray-600 font-medium truncate">智能工作流平台</p>
              </div>
            </div>
          )}
          {isCollapsed && (
            <div className="flex justify-center w-full">
              <div className="relative">
                <div className="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 via-blue-600 to-gray-500 rounded-xl flex items-center justify-center shadow-medium">
                  <svg className="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <div className="absolute -top-1 -right-1 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-400 border-2 border-white rounded-full"></div>
              </div>
            </div>
          )}
          <div className="flex items-center space-x-1">
            {/* Mobile close button */}
            <button
              onClick={() => onMobileMenuToggle(false)}
              className="sm:hidden p-1.5 rounded-lg hover:bg-white/50 transition-all duration-300 text-gray-500 hover:text-gray-700 flex-shrink-0"
              title="关闭菜单"
            >
              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            {/* Desktop collapse button */}
            <button
              onClick={toggleSidebar}
              className="hidden sm:block p-1.5 sm:p-2.5 rounded-lg sm:rounded-xl hover:bg-white/50 transition-all duration-300 text-gray-500 hover:text-gray-700 hover:shadow-soft group flex-shrink-0"
              title={isCollapsed ? '展开侧边栏' : '收起侧边栏'}
            >
              <svg className="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={isCollapsed ? "M9 5l7 7-7 7" : "M15 19l-7-7 7-7"} />
              </svg>
            </button>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav className="relative flex-1 px-3 sm:px-4 py-4 sm:py-6 overflow-y-auto scrollbar-hide">
        <div className="space-y-1 sm:space-y-2">
          {menuItems.map((item, index) => {
            const isActive = location.pathname === item.path;
            return (
              <Link
                key={item.path}
                to={item.path}
                className={`group relative flex items-center px-3 sm:px-4 py-3 sm:py-4 text-xs sm:text-sm font-semibold rounded-xl sm:rounded-2xl transition-all duration-300 transform hover:scale-[1.02] ${
                  isActive
                    ? 'bg-gradient-to-r from-blue-500/10 via-blue-400/5 to-gray-500/10 text-blue-700 shadow-medium border border-blue-200/50'
                    : 'text-gray-700 hover:bg-white/50 hover:text-gray-900 hover:shadow-soft'
                }`}
                title={isCollapsed ? item.name : ''}
                style={{
                  animationDelay: `${index * 50}ms`
                }}
              >
                {/* Active indicator */}
                {isActive && (
                  <div className="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-r-full shadow-glow-blue"></div>
                )}
                
                {/* Icon container */}
                <div className={`${isCollapsed ? 'mx-auto' : 'mr-3 sm:mr-4'} relative flex-shrink-0`}>
                  <div className={`w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center transition-all duration-300 ${
                    isActive 
                      ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-medium' 
                      : 'bg-gray-100/50 text-gray-500 group-hover:bg-white group-hover:text-blue-600 group-hover:shadow-soft'
                  }`}>
                    {item.icon}
                  </div>
                  {isActive && (
                    <div className="absolute -top-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 bg-green-400 border-2 border-white rounded-full animate-pulseSoft"></div>
                  )}
                </div>
                
                {/* Text content */}
                {!isCollapsed && (
                  <div className="flex-1 min-w-0">
                    <div className="font-bold truncate text-sm sm:text-base">{item.name}</div>
                    <div className={`text-xs sm:text-sm mt-0.5 sm:mt-1 truncate font-medium hidden sm:block ${
                      isActive ? 'text-blue-600/80' : 'text-gray-500 group-hover:text-gray-600'
                    }`}>
                      {item.description}
                    </div>
                  </div>
                )}
                
                {/* Arrow indicator for active item */}
                {!isCollapsed && isActive && (
                  <div className="flex-shrink-0 ml-2">
                    <svg className="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                  </div>
                )}
                
                {/* Enhanced tooltip for collapsed state */}
                {isCollapsed && (
                  <div className="absolute left-full ml-2 sm:ml-4 px-2 sm:px-4 py-2 sm:py-3 bg-gray-900/95 backdrop-blur-sm text-white text-xs sm:text-sm rounded-lg sm:rounded-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 whitespace-nowrap z-50 shadow-strong">
                    <div className="font-bold">{item.name}</div>
                    <div className="text-xs text-gray-300 mt-0.5 sm:mt-1 hidden sm:block">{item.description}</div>
                    {/* Enhanced arrow */}
                    <div className="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 w-0 h-0 border-t-[6px] border-b-[6px] border-r-[8px] border-t-transparent border-b-transparent border-r-gray-900/95"></div>
                  </div>
                )}
              </Link>
            );
          })}
        </div>
        
        {/* Quick actions section */}
        {!isCollapsed && (
          <div className="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-white/10">
            <div className="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 sm:mb-3 px-3 sm:px-4">快速操作</div>
            <div className="space-y-1 sm:space-y-2">
              <button className="w-full flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-lg sm:rounded-xl transition-all duration-200 group">
                <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gray-100 rounded-md sm:rounded-lg flex items-center justify-center mr-2 sm:mr-3 group-hover:bg-white group-hover:shadow-soft transition-all duration-200">
                  <svg className="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </div>
                新建工作流
              </button>
              <button className="w-full flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-lg sm:rounded-xl transition-all duration-200 group">
                <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gray-100 rounded-md sm:rounded-lg flex items-center justify-center mr-2 sm:mr-3 group-hover:bg-white group-hover:shadow-soft transition-all duration-200">
                  <svg className="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                </div>
                性能监控
              </button>
            </div>
          </div>
        )}
      </nav>

      {/* Footer */}
      <div className="relative p-4 sm:p-6 border-t border-white/20 bg-gradient-to-r from-white/5 to-white/10 backdrop-blur-sm">
        {/* Background decoration */}
        <div className="absolute inset-0 bg-gradient-to-t from-blue-500/5 to-transparent"></div>
        
        {!isCollapsed ? (
          <div className="relative space-y-4">
            {/* System status */}
            <div className="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 bg-white/30 rounded-lg sm:rounded-xl backdrop-blur-sm border border-white/20">
              <div className="relative">
                <div className="w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulseSoft shadow-glow-green"></div>
                <div className="absolute inset-0 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-400/30 rounded-full animate-ping"></div>
              </div>
              <div className="flex-1 min-w-0">
                <div className="text-xs sm:text-sm font-bold text-gray-900 truncate">系统运行正常</div>
                <div className="text-xs text-gray-600 font-medium truncate hidden sm:block">最后更新: 刚刚</div>
              </div>
              <div className="text-xs text-green-600 font-bold bg-green-50 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-md sm:rounded-lg">
                99.9%
              </div>
            </div>
            
            {/* Performance metrics */}
            <div className="grid grid-cols-2 gap-2 sm:gap-3">
              <div className="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm border border-white/20">
                <div className="text-xs text-gray-500 font-medium">CPU</div>
                <div className="text-sm sm:text-lg font-bold text-gray-900">23%</div>
                <div className="w-full bg-gray-200 rounded-full h-1 sm:h-1.5 mt-1">
                  <div className="bg-gradient-to-r from-blue-400 to-blue-600 h-1 sm:h-1.5 rounded-full" style={{width: '23%'}}></div>
                </div>
              </div>
              <div className="p-2 sm:p-3 bg-white/20 rounded-lg sm:rounded-xl backdrop-blur-sm border border-white/20">
                <div className="text-xs text-gray-500 font-medium">内存</div>
                <div className="text-sm sm:text-lg font-bold text-gray-900">67%</div>
                <div className="w-full bg-gray-200 rounded-full h-1 sm:h-1.5 mt-1">
                  <div className="bg-gradient-to-r from-purple-400 to-purple-600 h-1 sm:h-1.5 rounded-full" style={{width: '67%'}}></div>
                </div>
              </div>
            </div>
            
            {/* User info */}
            <div className="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 bg-white/30 rounded-lg sm:rounded-xl backdrop-blur-sm border border-white/20">
              <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-medium">
                U
              </div>
              <div className="flex-1 min-w-0">
                <div className="text-xs sm:text-sm font-bold text-gray-900 truncate">开发者</div>
                <div className="text-xs text-gray-600 truncate hidden sm:block">admin@example.com</div>
              </div>
              <button className="p-1 sm:p-1.5 text-gray-400 hover:text-gray-600 hover:bg-white/50 rounded-md sm:rounded-lg transition-all duration-200">
                <svg className="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>
            </div>
          </div>
        ) : (
          <div className="relative flex flex-col items-center space-y-2 sm:space-y-3">
            {/* System status indicator */}
            <div className="relative" title="系统运行正常 - 99.9%">
              <div className="w-3 h-3 sm:w-4 sm:h-4 bg-green-400 rounded-full animate-pulseSoft shadow-glow-green"></div>
              <div className="absolute inset-0 w-3 h-3 sm:w-4 sm:h-4 bg-green-400/30 rounded-full animate-ping"></div>
            </div>
            
            {/* Mini performance indicators */}
            <div className="flex space-x-1">
              <div className="w-1 h-4 sm:h-6 bg-blue-400 rounded-full" title="CPU: 23%"></div>
              <div className="w-1 h-4 sm:h-6 bg-purple-400 rounded-full" title="内存: 67%"></div>
            </div>
            
            {/* User avatar */}
            <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-medium" title="开发者">
              U
            </div>
          </div>
        )}
      </div>
    </div>
    </>
  );
};

export default Sidebar;