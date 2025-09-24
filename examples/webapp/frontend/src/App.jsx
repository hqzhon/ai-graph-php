import React, { useState } from 'react';
import { Routes, Route } from 'react-router-dom';
import './App.css';
import Sidebar from './components/layout/Sidebar';
import LangGraphDemo from './LangGraphDemo';
import ArticleWorkflow from './ArticleWorkflow';
import ModelTest from './ModelTest';
import MultiAgentWorkflow from './MultiAgentWorkflow';
import ChatInterface from './ChatInterface';
import WorkflowValidation from './WorkflowValidation';

function App() {
  const [sidebarCollapsed, setSidebarCollapsed] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <div className="flex h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50/50 overflow-hidden">
      <Sidebar 
        isCollapsed={sidebarCollapsed} 
        onToggle={setSidebarCollapsed}
        mobileMenuOpen={mobileMenuOpen}
        onMobileMenuToggle={setMobileMenuOpen}
      />
      
      <main className="flex-1 flex flex-col overflow-hidden relative min-w-0">
        {/* Enhanced glass morphism header */}
        <header className="relative glass-effect border-b border-white/20 px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 lg:py-4 sticky top-0 z-20 backdrop-blur-xl">
          {/* Header background decoration */}
          <div className="absolute inset-0 bg-gradient-to-r from-white/10 via-white/5 to-white/10 pointer-events-none"></div>
          
          <div className="relative flex items-center justify-between">
            <div className="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 min-w-0 flex-1">
              {/* Desktop sidebar toggle */}
              <button
                onClick={() => setSidebarCollapsed(!sidebarCollapsed)}
                className="hidden sm:flex items-center justify-center p-1.5 lg:p-2 rounded-lg lg:rounded-xl hover:bg-white/50 transition-all duration-200 group"
                title="切换侧边栏"
              >
                <svg className="w-5 h-5 lg:w-6 lg:h-6 text-secondary-600 group-hover:text-secondary-800 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
              
              {/* Mobile menu toggle */}
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="sm:hidden flex items-center justify-center p-1.5 rounded-lg hover:bg-white/50 transition-all duration-200 group"
                title="打开菜单"
              >
                <svg className="w-5 h-5 text-secondary-600 group-hover:text-secondary-800 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
              
              {/* Title section with enhanced styling */}
              <div className="flex flex-col min-w-0 flex-1">
                <h1 className="text-base sm:text-lg lg:text-xl font-bold text-secondary-900 tracking-tight truncate bg-gradient-to-r from-secondary-900 to-secondary-700 bg-clip-text">
                  LangGraph 演示平台
                </h1>
                <p className="text-xs sm:text-sm text-secondary-600/80 mt-0.5 font-medium truncate">
                  基于 PHP SDK 的智能工作流演示系统
                </p>
              </div>
              
              {/* Enhanced breadcrumb indicator */}
              <div className="hidden xl:flex items-center space-x-3 ml-6">
                <div className="flex items-center space-x-2 px-3 py-1.5 bg-gradient-to-r from-primary-50 to-primary-100/50 rounded-lg border border-primary-200/30">
                <div className="w-2 h-2 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full animate-pulseSoft"></div>
                <span className="text-sm text-primary-700 font-medium">实时演示环境</span>
                </div>
              </div>
            </div>
            
            {/* Enhanced right section */}
            <div className="flex items-center space-x-1 sm:space-x-2 lg:space-x-3">
              {/* Enhanced performance indicator */}
              <div className="hidden lg:flex items-center space-x-2 px-3 py-2 bg-gradient-to-r from-success-50 via-success-50/80 to-success-50 text-success-700 rounded-xl text-sm border border-success-200/50 shadow-soft backdrop-blur-sm">
                <div className="w-2 h-2 bg-success-500 rounded-full animate-pulseSoft shadow-sm"></div>
                <span className="font-semibold">系统正常</span>
                <div className="w-px h-4 bg-success-200/70"></div>
                <span className="text-xs text-success-600 font-medium">99.9%</span>
              </div>
              
              {/* Enhanced tablet status */}
              <div className="hidden md:flex lg:hidden items-center space-x-1.5 px-2.5 py-1.5 bg-success-50/80 text-success-700 rounded-lg text-xs border border-success-200/30">
                <div className="w-2 h-2 bg-success-500 rounded-full animate-pulseSoft"></div>
                <span className="font-medium">正常</span>
              </div>
              
              {/* Enhanced mobile status */}
              <div className="flex md:hidden items-center p-1.5 bg-success-50/50 rounded-lg">
                <div className="w-2 h-2 bg-success-500 rounded-full animate-pulseSoft"></div>
              </div>
              
              {/* Enhanced notification bell */}
              <button className="relative p-1.5 sm:p-2 text-secondary-500 hover:text-secondary-700 hover:bg-white/60 rounded-xl transition-all duration-200 group" title="通知">
                <svg className="w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <div className="absolute -top-0.5 -right-0.5 w-3 h-3 bg-gradient-to-r from-red-500 to-red-600 rounded-full border-2 border-white group-hover:scale-110 transition-transform shadow-sm"></div>
              </button>
              
              {/* Enhanced user profile section */}
              <div className="flex items-center space-x-2 sm:space-x-3 pl-2 sm:pl-3 border-l border-neutral-200/50">
                <div className="hidden md:block text-right">
                  <div className="text-sm font-semibold text-neutral-900">开发者</div>
                  <div className="text-xs text-neutral-500 font-medium">管理员权限</div>
                </div>
                <div className="relative group">
                  <div className="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 bg-gradient-to-br from-primary-500 via-primary-600 to-secondary-500 rounded-xl flex items-center justify-center shadow-medium hover:shadow-strong transition-all duration-300 cursor-pointer group-hover:scale-105 border border-white/20">
                    <svg className="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div className="absolute top-0 right-0 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-success-400 border-2 border-white rounded-full"></div>
                </div>
              </div>
            </div>
          </div>
        </header>
        
        {/* Enhanced main content area */}
        <div className="flex-1 overflow-hidden relative">
          {/* Multi-layered background decoration */}
          <div className="absolute inset-0 bg-gradient-to-br from-primary-50/20 via-white/30 to-neutral-50/15 pointer-events-none"></div>
            <div className="absolute top-0 right-0 w-72 h-72 sm:w-96 sm:h-96 bg-gradient-to-bl from-primary-100/15 via-primary-50/10 to-transparent rounded-full blur-3xl pointer-events-none animate-float"></div>
            <div className="absolute bottom-0 left-0 w-72 h-72 sm:w-96 sm:h-96 bg-gradient-to-tr from-neutral-100/15 via-neutral-50/10 to-transparent rounded-full blur-3xl pointer-events-none animate-floatReverse"></div>
            <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 sm:w-80 sm:h-80 bg-gradient-to-r from-primary-50/10 to-neutral-50/10 rounded-full blur-2xl pointer-events-none"></div>
          
          {/* Content container with enhanced styling */}
          <div className="relative h-full overflow-y-auto scrollbar-hide">
            <div className="container mx-auto px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-6 max-w-7xl">
              <Routes>
                <Route path="/" element={<LangGraphDemo />} />
                <Route path="/article" element={<ArticleWorkflow />} />
                <Route path="/model-testing" element={<ModelTest />} />
                <Route path="/multi-agent" element={<MultiAgentWorkflow />} />
                <Route path="/chat" element={
                  <div className="h-[calc(100vh-120px)] sm:h-[calc(100vh-140px)] lg:h-[calc(100vh-160px)] bg-white/60 backdrop-blur-md rounded-xl lg:rounded-2xl border border-white/30 shadow-soft overflow-hidden relative">
                    <div className="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                    <div className="relative h-full">
                      <ChatInterface />
                    </div>
                  </div>
                } />
                <Route path="/workflow-validation" element={
                  <div className="h-[calc(100vh-120px)] sm:h-[calc(100vh-140px)] lg:h-[calc(100vh-160px)] bg-white/60 backdrop-blur-md rounded-xl lg:rounded-2xl border border-white/30 shadow-soft overflow-hidden relative">
                    <div className="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                    <div className="relative h-full">
                      <WorkflowValidation />
                    </div>
                  </div>
                } />
              </Routes>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}

export default App;
