import React, { useState } from 'react';
import Card from './components/ui/Card';
import Button from './components/ui/Button';

const LangGraphDemo = () => {
  const [simpleResult, setSimpleResult] = useState(null);
  const [advancedResult, setAdvancedResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const executeSimpleWorkflow = async () => {
    setLoading(true);
    try {
      const response = await fetch('http://localhost:8000/api/langgraph/simple-workflow', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();
      setSimpleResult(data);
    } catch (error) {
      setSimpleResult({ success: false, error: error.message });
    }
    setLoading(false);
  };

  const executeAdvancedWorkflow = async () => {
    setLoading(true);
    try {
      const response = await fetch('http://localhost:8000/api/langgraph/advanced-workflow', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();
      setAdvancedResult(data);
    } catch (error) {
      setAdvancedResult({ success: false, error: error.message });
    }
    setLoading(false);
  };

  return (
    <div className="space-y-6 sm:space-y-10">
      {/* Enhanced Header */}
      <div className="relative mb-8 sm:mb-12">
        {/* Background decoration */}
        <div className="absolute -top-2 -left-2 sm:-top-4 sm:-left-4 w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-br from-primary-200/30 to-secondary-200/30 rounded-full blur-xl sm:blur-2xl"></div>
        <div className="absolute -top-1 -right-4 sm:-top-2 sm:-right-8 w-20 h-20 sm:w-32 sm:h-32 bg-gradient-to-bl from-secondary-200/20 to-primary-200/20 rounded-full blur-2xl sm:blur-3xl"></div>
        
        <div className="relative">
          <div className="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-4 sm:mb-6">
            <div className="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-primary-500 via-primary-600 to-secondary-500 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-strong">
              <svg className="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <div className="flex-1">
              <h1 className="text-2xl sm:text-3xl lg:text-4xl font-black bg-gradient-to-r from-primary-600 via-primary-700 to-secondary-600 bg-clip-text text-transparent mb-2">
                LangGraph 工作流演示
              </h1>
              <div className="flex items-center space-x-2">
                <div className="w-2 h-2 bg-success-400 rounded-full animate-pulseSoft"></div>
                <span className="text-xs sm:text-sm font-semibold text-success-600">系统在线</span>
              </div>
            </div>
          </div>
          <p className="text-base sm:text-lg lg:text-xl text-secondary-700 max-w-4xl leading-relaxed font-medium">
            体验基于 PHP SDK 的智能工作流系统，支持简单和高级工作流模式，
            <span className="text-primary-600 font-semibold">让AI工作流变得简单而强大</span>
          </p>
        </div>
      </div>

      {/* Enhanced Features Overview */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mb-8 sm:mb-12">
        <div className="group relative bg-white/80 backdrop-blur-sm p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border border-neutral-200/50 shadow-soft hover:shadow-strong transition-all duration-500 hover:-translate-y-2">
          {/* Background decoration */}
          <div className="absolute -top-2 -right-2 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-primary-200/30 to-primary-200/30 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500"></div>
          
          <div className="relative">
            <div className="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-strong group-hover:scale-110 transition-transform duration-300">
              <svg className="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 className="text-lg sm:text-xl font-bold text-neutral-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">简单易用</h3>
            <p className="text-sm sm:text-base text-neutral-600 leading-relaxed">直观的工作流设计，快速上手，让复杂的AI工作流变得触手可及</p>
          </div>
        </div>
        
        <div className="group relative bg-white/80 backdrop-blur-sm p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border border-neutral-200/50 shadow-soft hover:shadow-strong transition-all duration-500 hover:-translate-y-2">
          {/* Background decoration */}
          <div className="absolute -top-2 -right-2 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-success-200/30 to-secondary-200/30 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500"></div>
          
          <div className="relative">
            <div className="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-success-500 to-secondary-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-strong group-hover:scale-110 transition-transform duration-300">
              <svg className="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 className="text-lg sm:text-xl font-bold text-neutral-900 mb-2 sm:mb-3 group-hover:text-secondary-600 transition-colors">高性能</h3>
            <p className="text-sm sm:text-base text-neutral-600 leading-relaxed">优化的执行引擎，毫秒级响应，为您的业务提供强劲动力</p>
          </div>
        </div>
        
        <div className="group relative bg-white/80 backdrop-blur-sm p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl border border-neutral-200/50 shadow-soft hover:shadow-strong transition-all duration-500 hover:-translate-y-2">
          {/* Background decoration */}
          <div className="absolute -top-2 -right-2 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-accent-200/30 to-accent-200/30 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500"></div>
          
          <div className="relative">
            <div className="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-strong group-hover:scale-110 transition-transform duration-300">
              <svg className="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <h3 className="text-lg sm:text-xl font-bold text-neutral-900 mb-2 sm:mb-3 group-hover:text-accent-600 transition-colors">灵活扩展</h3>
            <p className="text-sm sm:text-base text-neutral-600 leading-relaxed">支持自定义节点和复杂工作流，轻松应对复杂场景和业务增长需求</p>
          </div>
        </div>
      </div>
      
      {/* Enhanced Workflow Cards */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10">
        {/* Simple Workflow */}
        <Card className="group relative overflow-hidden bg-white/90 backdrop-blur-sm border-neutral-200/50 shadow-soft hover:shadow-strong transition-all duration-500 hover:-translate-y-1">
          {/* Background decoration */}
          <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-primary-200/20 to-primary-200/20 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-700"></div>
          <div className="absolute -bottom-4 -left-4 w-24 h-24 bg-gradient-to-tr from-primary-200/15 to-primary-200/15 rounded-full blur-xl group-hover:blur-2xl transition-all duration-700"></div>
          
          <Card.Header className="relative">
            <div className="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4 lg:space-x-6">
              <div className="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-primary-500 via-primary-600 to-primary-600 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-strong group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                <svg className="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div className="flex-1">
                <div className="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 mb-3">
                  <h3 className="text-xl sm:text-2xl font-bold text-neutral-900 group-hover:text-primary-600 transition-colors">简单工作流</h3>
                  <div className="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full self-start">
                    推荐入门
                  </div>
                </div>
                <p className="text-neutral-600 leading-relaxed text-sm sm:text-base lg:text-lg">
                  执行基础的三节点 LangGraph 工作流，适合快速验证和学习，
                  <span className="text-primary-600 font-medium">零基础也能轻松上手</span>
                </p>
              </div>
            </div>
          </Card.Header>
          
          <Card.Content className="relative">
            <div className="space-y-5 mb-8">
              <div className="flex items-center justify-between py-4 px-4 bg-neutral-50/50 rounded-xl border border-neutral-100/50 hover:bg-neutral-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-primary-400 rounded-full"></div>
                  <span className="text-neutral-700 font-medium">节点数量</span>
                </div>
                <div className="px-3 py-1 bg-primary-100 text-primary-700 text-sm font-bold rounded-lg">3个</div>
              </div>
              <div className="flex items-center justify-between py-4 px-4 bg-neutral-50/50 rounded-xl border border-neutral-100/50 hover:bg-neutral-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-success-400 rounded-full"></div>
                  <span className="text-neutral-700 font-medium">执行模式</span>
                </div>
                <div className="px-3 py-1 bg-success-100 text-success-700 text-sm font-bold rounded-lg">顺序执行</div>
              </div>
              <div className="flex items-center justify-between py-4 px-4 bg-neutral-50/50 rounded-xl border border-neutral-100/50 hover:bg-neutral-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-accent-400 rounded-full"></div>
                  <span className="text-neutral-700 font-medium">状态管理</span>
                </div>
                <div className="px-3 py-1 bg-accent-100 text-accent-700 text-sm font-bold rounded-lg">基础状态</div>
              </div>
            </div>
            
            <Button
              onClick={executeSimpleWorkflow}
              disabled={loading}
              loading={loading}
              className="w-full h-12 sm:h-14 text-base sm:text-lg font-semibold bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-strong hover:shadow-xl transition-all duration-300 group"
            >
              <span className="group-hover:scale-105 transition-transform duration-200">
                {loading ? '执行中...' : '🚀 执行简单工作流'}
              </span>
            </Button>
            
            {simpleResult && (
              <div className="mt-6 sm:mt-8 p-4 sm:p-6 bg-gradient-to-br from-neutral-50 to-primary-50/30 rounded-xl sm:rounded-2xl border border-neutral-200/50 shadow-soft">
                <h4 className="font-bold text-neutral-900 mb-3 sm:mb-4 flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                  <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-success-500 to-success-600 rounded-lg flex items-center justify-center">
                    <svg className="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                  </div>
                  <span className="text-base sm:text-lg">执行结果</span>
                  <div className="px-2 py-1 bg-success-100 text-success-700 text-xs font-semibold rounded-full">
                    成功
                  </div>
                </h4>
                <div className="bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl border border-gray-200/50 overflow-hidden">
                  <pre className="text-xs sm:text-sm text-gray-700 overflow-auto max-h-48 sm:max-h-64 p-3 sm:p-4 lg:p-6 font-mono leading-relaxed">
                    {JSON.stringify(simpleResult, null, 2)}
                  </pre>
                </div>
              </div>
            )}
          </Card.Content>
        </Card>
        
        {/* Advanced Workflow */}
        <Card className="group relative overflow-hidden bg-white/90 backdrop-blur-sm border-neutral-200/50 shadow-soft hover:shadow-strong transition-all duration-500 hover:-translate-y-1">
          {/* Background decoration */}
          <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-success-200/20 to-success-200/20 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-700"></div>
          <div className="absolute -bottom-4 -left-4 w-24 h-24 bg-gradient-to-tr from-success-200/15 to-success-200/15 rounded-full blur-xl group-hover:blur-2xl transition-all duration-700"></div>
          
          <Card.Header className="relative">
            <div className="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4 lg:space-x-6">
              <div className="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-success-500 via-success-600 to-success-600 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-strong group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                <svg className="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
              </div>
              <div className="flex-1">
                <div className="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 mb-3">
                  <h3 className="text-xl sm:text-2xl font-bold text-neutral-900 group-hover:text-success-600 transition-colors">高级工作流</h3>
                  <div className="px-3 py-1 bg-success-100 text-success-700 text-xs font-semibold rounded-full self-start">
                    专业级
                  </div>
                </div>
                <p className="text-neutral-600 leading-relaxed text-sm sm:text-base lg:text-lg">
                  执行基于通道的高级状态管理工作流，支持复杂的业务逻辑，
                  <span className="text-success-600 font-medium">释放AI的无限潜能</span>
                </p>
              </div>
            </div>
          </Card.Header>
          
          <Card.Content className="relative">
            <div className="space-y-5 mb-8">
              <div className="flex items-center justify-between py-4 px-4 bg-neutral-50/50 rounded-xl border border-neutral-100/50 hover:bg-neutral-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-success-400 rounded-full"></div>
                  <span className="text-neutral-700 font-medium">节点数量</span>
                </div>
                <div className="px-3 py-1 bg-success-100 text-success-700 text-sm font-bold rounded-lg">多个</div>
              </div>
              <div className="flex items-center justify-between py-4 px-4 bg-neutral-50/50 rounded-xl border border-neutral-100/50 hover:bg-neutral-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-warning-400 rounded-full"></div>
                  <span className="text-neutral-700 font-medium">执行模式</span>
                </div>
                <div className="px-3 py-1 bg-warning-100 text-warning-700 text-sm font-bold rounded-lg">并行 + 条件</div>
              </div>
              <div className="flex items-center justify-between py-4 px-4 bg-neutral-50/50 rounded-xl border border-neutral-100/50 hover:bg-neutral-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-accent-400 rounded-full"></div>
                  <span className="text-neutral-700 font-medium">状态管理</span>
                </div>
                <div className="px-3 py-1 bg-accent-100 text-accent-700 text-sm font-bold rounded-lg">通道状态</div>
              </div>
            </div>
            
            <Button
              onClick={executeAdvancedWorkflow}
              disabled={loading}
              loading={loading}
              variant="success"
              className="w-full h-12 sm:h-14 text-base sm:text-lg font-semibold bg-gradient-to-r from-success-500 to-success-600 hover:from-success-600 hover:to-success-700 shadow-strong hover:shadow-xl transition-all duration-300 group"
            >
              <span className="group-hover:scale-105 transition-transform duration-200">
                {loading ? '执行中...' : '⚡ 执行高级工作流'}
              </span>
            </Button>
            
            {advancedResult && (
              <div className="mt-6 sm:mt-8 p-4 sm:p-6 bg-gradient-to-br from-neutral-50 to-success-50/30 rounded-xl sm:rounded-2xl border border-neutral-200/50 shadow-soft">
                <h4 className="font-bold text-neutral-900 mb-3 sm:mb-4 flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                  <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-success-500 to-success-600 rounded-lg flex items-center justify-center">
                    <svg className="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                  </div>
                  <span className="text-base sm:text-lg">执行结果</span>
                  <div className="px-2 py-1 bg-success-100 text-success-700 text-xs font-semibold rounded-full">
                    成功
                  </div>
                </h4>
                <div className="bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl border border-neutral-200/50 overflow-hidden">
                  <pre className="text-xs sm:text-sm text-neutral-700 overflow-auto max-h-48 sm:max-h-64 p-3 sm:p-4 lg:p-6 font-mono leading-relaxed">
                    {JSON.stringify(advancedResult, null, 2)}
                  </pre>
                </div>
              </div>
            )}
          </Card.Content>
        </Card>
        
        {/* Advanced Workflow */}
        <Card className="group relative overflow-hidden bg-white/90 backdrop-blur-sm border-neutral-200/50 shadow-soft hover:shadow-strong transition-all duration-500 hover:-translate-y-1">
          {/* Background decoration */}
          <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-success-200/20 to-success-200/20 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-700"></div>
          <div className="absolute -bottom-4 -left-4 w-24 h-24 bg-gradient-to-tr from-success-200/15 to-success-200/15 rounded-full blur-xl group-hover:blur-2xl transition-all duration-700"></div>
          
          <Card.Header className="relative">
            <div className="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4 lg:space-x-6">
              <div className="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-success-500 via-success-600 to-success-600 rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-strong group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                <svg className="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
              </div>
              <div className="flex-1">
                <div className="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 mb-3">
                  <h3 className="text-xl sm:text-2xl font-bold text-neutral-900 group-hover:text-success-600 transition-colors">高级工作流</h3>
                  <div className="px-3 py-1 bg-success-100 text-success-700 text-xs font-semibold rounded-full self-start">
                    专业级
                  </div>
                </div>
                <p className="text-gray-600 leading-relaxed text-sm sm:text-base lg:text-lg">
                  执行基于通道的高级状态管理工作流，支持复杂的业务逻辑，
                  <span className="text-success-600 font-medium">释放AI的无限潜能</span>
                </p>
              </div>
            </div>
          </Card.Header>
          
          <Card.Content className="relative">
            <div className="space-y-5 mb-8">
              <div className="flex items-center justify-between py-4 px-4 bg-gray-50/50 rounded-xl border border-gray-100/50 hover:bg-gray-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-green-400 rounded-full"></div>
                  <span className="text-gray-700 font-medium">节点数量</span>
                </div>
                <div className="px-3 py-1 bg-green-100 text-green-700 text-sm font-bold rounded-lg">多个</div>
              </div>
              <div className="flex items-center justify-between py-4 px-4 bg-gray-50/50 rounded-xl border border-gray-100/50 hover:bg-gray-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-orange-400 rounded-full"></div>
                  <span className="text-gray-700 font-medium">执行模式</span>
                </div>
                <div className="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-bold rounded-lg">并行 + 条件</div>
              </div>
              <div className="flex items-center justify-between py-4 px-4 bg-gray-50/50 rounded-xl border border-gray-100/50 hover:bg-gray-50 transition-colors">
                <div className="flex items-center space-x-3">
                  <div className="w-2 h-2 bg-purple-400 rounded-full"></div>
                  <span className="text-gray-700 font-medium">状态管理</span>
                </div>
                <div className="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-bold rounded-lg">通道状态</div>
              </div>
            </div>
            
            <Button
              onClick={executeAdvancedWorkflow}
              disabled={loading}
              loading={loading}
              variant="success"
              className="w-full h-12 sm:h-14 text-base sm:text-lg font-semibold bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 shadow-strong hover:shadow-xl transition-all duration-300 group"
            >
              <span className="group-hover:scale-105 transition-transform duration-200">
                {loading ? '执行中...' : '⚡ 执行高级工作流'}
              </span>
            </Button>
            
            {advancedResult && (
              <div className="mt-6 sm:mt-8 p-4 sm:p-6 bg-gradient-to-br from-gray-50 to-green-50/30 rounded-xl sm:rounded-2xl border border-gray-200/50 shadow-soft">
                <h4 className="font-bold text-gray-900 mb-3 sm:mb-4 flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                  <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <svg className="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                  </div>
                  <span className="text-base sm:text-lg">执行结果</span>
                  <div className="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                    成功
                  </div>
                </h4>
                <div className="bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl border border-gray-200/50 overflow-hidden">
                  <pre className="text-xs sm:text-sm text-gray-700 overflow-auto max-h-48 sm:max-h-64 p-3 sm:p-4 lg:p-6 font-mono leading-relaxed">
                    {JSON.stringify(advancedResult, null, 2)}
                  </pre>
                </div>
              </div>
            )}
          </Card.Content>
        </Card>
      </div>

      {/* Tips Section */}
      <Card className="relative overflow-hidden bg-gradient-to-br from-blue-50/80 via-indigo-50/60 to-purple-50/40 border-blue-200/50 shadow-soft backdrop-blur-sm">
        {/* Background decoration */}
        <div className="absolute -top-6 -right-6 w-40 h-40 bg-gradient-to-br from-blue-200/30 to-indigo-200/20 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-tr from-indigo-300/20 to-accent-200/15 rounded-full blur-2xl"></div>
        
        <Card.Header className="relative">
          <div className="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
            <div className="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-strong">
              <svg className="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 className="text-xl sm:text-2xl font-bold bg-gradient-to-r from-primary-600 via-primary-700 to-accent-600 bg-clip-text text-transparent">
                使用提示
              </h3>
              <p className="text-primary-700/80 mt-1 text-sm sm:text-base">掌握这些技巧，让你的工作流更加高效</p>
            </div>
          </div>
        </Card.Header>
        
        <Card.Content className="relative">
          <div className="grid gap-4">
            <div className="group flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-blue-200/30 hover:bg-white/80 hover:border-blue-300/50 transition-all duration-300">
              <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                <div className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-white rounded-full"></div>
              </div>
              <div>
                <h4 className="font-semibold text-blue-900 mb-1 text-sm sm:text-base">快速入门</h4>
                <p className="text-blue-800/90 text-xs sm:text-sm">简单工作流适合快速测试和学习基本概念，是初学者的最佳选择</p>
              </div>
            </div>
            
            <div className="group flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-indigo-200/30 hover:bg-white/80 hover:border-indigo-300/50 transition-all duration-300">
              <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                <div className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-white rounded-full"></div>
              </div>
              <div>
                <h4 className="font-semibold text-indigo-900 mb-1 text-sm sm:text-base">进阶应用</h4>
                <p className="text-indigo-800/90 text-xs sm:text-sm">高级工作流展示了复杂的状态管理和条件执行，适合生产环境</p>
              </div>
            </div>
            
            <div className="group flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-purple-200/30 hover:bg-white/80 hover:border-purple-300/50 transition-all duration-300">
              <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                <div className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-white rounded-full"></div>
              </div>
              <div>
                <h4 className="font-semibold text-purple-900 mb-1 text-sm sm:text-base">调试技巧</h4>
                <p className="text-purple-800/90 text-xs sm:text-sm">查看控制台输出可以了解工作流的详细执行过程和性能指标</p>
              </div>
            </div>
            
            <div className="group flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-pink-200/30 hover:bg-white/80 hover:border-pink-300/50 transition-all duration-300">
              <div className="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                <div className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-white rounded-full"></div>
              </div>
              <div>
                <h4 className="font-semibold text-pink-900 mb-1 text-sm sm:text-base">自定义开发</h4>
                <p className="text-pink-800/90 text-xs sm:text-sm">可以修改工作流代码来实验不同的执行逻辑，释放创造力</p>
              </div>
            </div>
          </div>
        </Card.Content>
      </Card>
    </div>
  );
};

export default LangGraphDemo;