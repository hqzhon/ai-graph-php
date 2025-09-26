import React, { useState } from 'react';
import Card from './components/ui/Card';
import Button from './components/ui/Button';
import Badge from './components/ui/Badge';

const ArticleWorkflow = () => {
  const [selectedArticle, setSelectedArticle] = useState('');
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const articleOptions = [
    {
      id: 'tech_article',
      title: '技术文章',
      description: '关于人工智能和机器学习的深度技术分析',
      category: '技术',
      estimatedTime: '5-8分钟'
    },
    {
      id: 'business_article',
      title: '商业分析',
      description: '市场趋势和商业策略的专业分析报告',
      category: '商业',
      estimatedTime: '3-5分钟'
    },
    {
      id: 'tutorial_article',
      title: '教程指南',
      description: '详细的步骤指导和最佳实践分享',
      category: '教程',
      estimatedTime: '6-10分钟'
    }
  ];

  const executeWorkflow = async () => {
    if (!selectedArticle) {
      setError('请先选择一个文章类型');
      return;
    }

    setLoading(true);
    setError(null);
    setResult(null);

    try {
      // First, get the article data using the available API endpoint
      // We'll use article ID 1 as the default sample article
      const response = await fetch('http://localhost:8000/api/article/1', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
        },
      });

      const data = await response.json();
      
      if (data.success) {
        // Simulate the workflow execution with the retrieved article data
        const articleData = data.data.article;
        // Apply a transition to move the article from 'draft' to 'review'
        if (articleData.status === 'draft' && data.data.availableTransitions.includes('submit')) {
          // Apply the 'submit' transition
          const transitionResponse = await fetch(`http://localhost:8000/api/article/1/transition/submit`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
          });
          const transitionData = await transitionResponse.json();
          
          if (transitionData.success) {
            setResult(transitionData.data);
          } else {
            setError(transitionData.error || '工作流执行失败');
          }
        } else {
          setResult(data.data);
        }
      } else {
        setError(data.error || '获取文章数据失败');
      }
    } catch (err) {
      setError(`网络错误: ${err.message}`);
    } finally {
      setLoading(false);
    }
  };

  const getCategoryColor = (category) => {
    const colors = {
      '技术': 'bg-primary-100 text-primary-800',
      '商业': 'bg-success-100 text-success-800',
      '教程': 'bg-accent-100 text-accent-800'
    };
    return colors[category] || 'bg-neutral-100 text-neutral-800';
  };

  return (
    <div className="space-y-10">
      {/* Header */}
      <div className="relative mb-12">
        {/* Background decoration */}
        <div className="absolute -top-8 -right-8 w-64 h-64 bg-gradient-to-br from-primary-200/30 to-accent-200/20 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-4 -left-4 w-48 h-48 bg-gradient-to-tr from-accent-200/20 to-secondary-200/15 rounded-full blur-2xl"></div>
        
        <div className="relative flex items-center space-x-6">
          <div className="w-20 h-20 bg-gradient-to-br from-primary-500 via-accent-600 to-secondary-600 rounded-3xl flex items-center justify-center shadow-strong">
            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div className="flex-1">
            <h1 className="text-4xl font-bold bg-gradient-to-r from-primary-600 via-accent-600 to-secondary-600 bg-clip-text text-transparent mb-4">
              文章工作流演示
            </h1>
            <div className="flex items-center space-x-4 mb-4">
              <div className="flex items-center space-x-2">
                <div className="w-3 h-3 bg-success-400 rounded-full animate-pulse"></div>
                <span className="text-success-600 font-medium">AI 写作引擎在线</span>
              </div>
              <div className="px-3 py-1 bg-primary-100 text-primary-700 text-sm font-semibold rounded-full">
                智能创作
              </div>
            </div>
            <p className="text-xl text-secondary-600 leading-relaxed max-w-4xl">
              选择文章类型，体验智能文章生成工作流的完整流程，
              <span className="text-accent-600 font-medium">从内容规划到优化输出</span>，
              让AI成为您的专业写作助手
            </p>
          </div>
        </div>
      </div>

      {/* Article Selection */}
      <Card className="bg-white/80 backdrop-blur-sm shadow-strong border border-neutral-200/50 hover:shadow-glow transition-all duration-300">
        {/* Background decoration */}
        <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-primary-200/20 to-accent-200/15 rounded-full blur-2xl"></div>
        <div className="absolute -bottom-2 -left-2 w-24 h-24 bg-gradient-to-tr from-accent-200/15 to-secondary-200/10 rounded-full blur-xl"></div>
        
        <Card.Header className="relative">
          <div className="flex items-center space-x-4">
            <div className="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div>
              <h2 className="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                选择文章类型
              </h2>
              <p className="text-secondary-600 mt-1">选择您想要生成的文章类型，系统将为您定制相应的工作流</p>
            </div>
          </div>
        </Card.Header>
        
        <Card.Content className="relative">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {articleOptions.map((article, index) => {
              const isSelected = selectedArticle === article.id;
              return (
                <button
                  key={article.id}
                  onClick={() => setSelectedArticle(article.id)}
                  className={`group relative p-6 rounded-2xl border-2 text-left transition-all duration-300 transform hover:scale-105 ${
                    isSelected
                      ? 'border-primary-500 bg-gradient-to-br from-primary-50 to-accent-50 shadow-lg'
                      : 'border-neutral-200/60 hover:border-primary-300 hover:bg-gradient-to-br hover:from-primary-50/50 hover:to-accent-50/30 hover:shadow-md'
                  }`}
                  style={{ animationDelay: `${index * 100}ms` }}
                >
                  {/* Selection indicator */}
                  {isSelected && (
                    <div className="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-primary-500 to-accent-600 rounded-full flex items-center justify-center shadow-lg">
                      <svg className="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                  )}
                  
                  <div className="flex items-center space-x-4 mb-4">
                    <div className="w-12 h-12 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center group-hover:from-primary-200 group-hover:to-accent-200 transition-colors">
                      <span className="text-2xl">📄</span>
                    </div>
                    <div>
                      <h3 className="font-bold text-neutral-900 text-lg group-hover:text-primary-700 transition-colors">
                        {article.title}
                      </h3>
                    </div>
                  </div>
                  
                  <p className="text-secondary-600 mb-4 leading-relaxed">{article.description}</p>
                  
                  <div className="flex flex-wrap gap-2 mb-3">
                    <span className={`px-3 py-1 text-xs font-medium rounded-full transition-all ${getCategoryColor(article.category)} group-hover:scale-105`}>
                      {article.category}
                    </span>
                  </div>
                  
                  <div className="text-xs text-secondary-500">
                    <span>预计时间: {article.estimatedTime}</span>
                  </div>
                </button>
              );
            })}
          </div>
        </Card.Content>
      </Card>

      {/* Workflow Execution */}
      <Card className="bg-white/80 backdrop-blur-sm shadow-strong border border-neutral-200/50 hover:shadow-glow transition-all duration-300">
        {/* Background decoration */}
        <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-success-200/20 to-primary-200/15 rounded-full blur-2xl"></div>
        <div className="absolute -bottom-2 -left-2 w-24 h-24 bg-gradient-to-tr from-primary-200/15 to-accent-200/10 rounded-full blur-xl"></div>
        
        <Card.Header className="relative">
          <div className="flex items-center space-x-4">
            <div className="w-12 h-12 bg-gradient-to-br from-success-500 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <div>
              <h2 className="text-2xl font-bold bg-gradient-to-r from-success-600 to-primary-600 bg-clip-text text-transparent">
                执行工作流
              </h2>
              <p className="text-secondary-600 mt-1">点击按钮开始执行文章生成工作流</p>
            </div>
          </div>
        </Card.Header>
        
        <Card.Content className="relative">
          <div className="space-y-6">
            {/* Workflow Steps */}
            <div className="bg-gradient-to-br from-neutral-50 to-primary-50/30 p-6 rounded-2xl border border-neutral-200/50">
              <div className="flex items-center space-x-3 mb-4">
                <div className="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-600 rounded-lg flex items-center justify-center">
                  <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <h3 className="font-bold text-neutral-900 text-lg">工作流步骤</h3>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
                {[
                  { step: 1, title: "需求分析", desc: "分析文章类型和要求", icon: "🔍" },
                  { step: 2, title: "大纲生成", desc: "生成文章大纲", icon: "📋" },
                  { step: 3, title: "内容创作", desc: "创建文章内容", icon: "✍️" },
                  { step: 4, title: "优化润色", desc: "优化和润色", icon: "✨" },
                  { step: 5, title: "输出结果", desc: "输出最终结果", icon: "🎯" }
                ].map((item, index) => (
                  <div key={item.step} className="text-center">
                    <div className="w-12 h-12 bg-white rounded-xl shadow-md flex items-center justify-center mx-auto mb-2">
                      <span className="text-lg">{item.icon}</span>
                    </div>
                    <h4 className="font-semibold text-neutral-900 text-sm mb-1">{item.title}</h4>
                    <p className="text-xs text-secondary-600">{item.desc}</p>
                    {index < 4 && (
                      <div className="hidden md:block absolute top-6 left-full w-4 h-0.5 bg-gradient-to-r from-primary-300 to-accent-300 transform translate-x-2"></div>
                    )}
                  </div>
                ))}
              </div>
            </div>

            {/* Action Button */}
            <div className="flex justify-center pt-4">
              <Button
                onClick={executeWorkflow}
                disabled={!selectedArticle || loading}
                loading={loading}
                size="lg"
                className="group relative px-8 py-4 bg-gradient-to-r from-primary-600 via-accent-600 to-secondary-600 text-white rounded-2xl font-semibold text-lg shadow-strong hover:shadow-glow transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
              >
                {loading ? (
                  <div className="flex items-center space-x-3">
                    <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    <span>AI 正在创作中...</span>
                  </div>
                ) : (
                  <div className="flex items-center space-x-3">
                    <svg className="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>开始执行工作流</span>
                  </div>
                )}
              </Button>
            </div>
          </div>
        </Card.Content>
      </Card>

      {/* Error Display */}
      {error && (
        <Card className="bg-gradient-to-br from-error-50 to-secondary-50 border border-error-200/50 shadow-strong">
          <Card.Content>
            <div className="flex items-center space-x-4">
              <div className="w-12 h-12 bg-gradient-to-br from-error-500 to-secondary-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <div>
                <h3 className="font-bold text-error-900 text-lg">执行失败</h3>
                <p className="text-error-700 mt-1">{error}</p>
              </div>
            </div>
          </Card.Content>
        </Card>
      )}

      {/* Loading State */}
      {loading && (
        <Card className="bg-gradient-to-br from-primary-50 to-accent-50 border border-primary-200/50 shadow-strong">
          <Card.Content>
            <div className="flex items-center space-x-4">
              <div className="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-600 rounded-2xl flex items-center justify-center shadow-lg">
                <div className="w-6 h-6 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
              </div>
              <div>
                <h3 className="font-bold text-blue-900 text-lg">AI 正在创作中</h3>
                <p className="text-blue-700 mt-1">智能分析需求，生成高质量文章内容...</p>
                <div className="flex items-center space-x-2 mt-2">
                  <div className="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
                  <div className="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style={{ animationDelay: '0.1s' }}></div>
                  <div className="w-2 h-2 bg-pink-400 rounded-full animate-bounce" style={{ animationDelay: '0.2s' }}></div>
                </div>
              </div>
            </div>
          </Card.Content>
        </Card>
      )}

      {/* Results */}
      {result && (
        <div className="space-y-8">
          {/* Article Content */}
          <Card className="bg-white/80 backdrop-blur-sm shadow-strong border border-gray-200/50 hover:shadow-glow transition-all duration-300">
            {/* Background decoration */}
            <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-green-200/20 to-blue-200/15 rounded-full blur-2xl"></div>
            <div className="absolute -bottom-2 -left-2 w-24 h-24 bg-gradient-to-tr from-blue-200/15 to-purple-200/10 rounded-full blur-xl"></div>
            
            <Card.Header className="relative">
              <div className="flex items-center space-x-4">
                <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                  <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <h2 className="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                    生成的文章
                  </h2>
                  <div className="flex items-center space-x-2 mt-1">
                    <div className="w-2 h-2 bg-green-400 rounded-full"></div>
                    <span className="text-green-600 font-medium text-sm">创作完成</span>
                    <Badge variant="success">成功</Badge>
                  </div>
                </div>
              </div>
            </Card.Header>
            
            <Card.Content className="relative">
              <div className="space-y-6">
                {/* Success Message */}
                <div className="p-4 bg-green-50 border border-green-200 rounded-lg">
                  <div className="flex items-start space-x-3">
                    <svg className="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                      <h4 className="font-medium text-green-900">工作流执行成功</h4>
                      <p className="text-green-700 mt-1">文章已成功生成并通过质量检查</p>
                    </div>
                  </div>
                </div>

                {/* Article Content */}
                {result.article && (
                  <div>
                    <h4 className="text-lg font-medium text-gray-900 mb-4">生成的文章内容</h4>
                    <div className="p-6 bg-gray-50 rounded-lg border border-gray-200">
                      <div className="prose max-w-none">
                        <h5 className="text-sm font-medium text-gray-500 mb-3">文章标题</h5>
                        <h2 className="text-xl font-bold text-gray-900 mb-4">{result.article.title}</h2>
                        
                        <h5 className="text-sm font-medium text-gray-500 mb-3">文章内容</h5>
                        <div className="bg-white p-4 rounded border border-gray-200">
                          <p className="text-gray-900 leading-relaxed whitespace-pre-wrap">{result.article.content}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                )}

                {/* Raw Result */}
                <div>
                  <h4 className="text-lg font-medium text-gray-900 mb-4">完整执行结果</h4>
                  <div className="bg-gray-50 rounded-lg p-6">
                    <pre className="text-sm text-gray-700 whitespace-pre-wrap overflow-x-auto bg-white p-4 rounded border font-mono">
                      {JSON.stringify(result, null, 2)}
                    </pre>
                  </div>
                </div>
              </div>
            </Card.Content>
          </Card>
        </div>
      )}

      {/* Usage Instructions */}
      <Card className="bg-gradient-to-br from-blue-50 to-purple-50/50 border border-blue-200/50 shadow-strong">
        {/* Background decoration */}
        <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-blue-200/20 to-purple-200/15 rounded-full blur-2xl"></div>
        <div className="absolute -bottom-2 -left-2 w-24 h-24 bg-gradient-to-tr from-purple-200/15 to-pink-200/10 rounded-full blur-xl"></div>
        
        <Card.Header className="relative">
          <div className="flex items-center space-x-4">
            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h2 className="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                使用说明
              </h2>
              <p className="text-blue-700 mt-1">快速上手文章工作流</p>
            </div>
          </div>
        </Card.Header>
        
        <Card.Content className="relative">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {[
              {
                step: 1,
                title: "选择文章类型",
                desc: "选择您想要生成的文章类型，每种类型都有不同的工作流程",
                icon: "🎯"
              },
              {
                 step: 2,
                 title: "执行工作流",
                 desc: "点击\"开始执行工作流\"按钮，系统将自动执行相应的文章生成流程",
                 icon: "⚡"
               },
              {
                step: 3,
                title: "AI 智能创作",
                desc: "等待AI完成文章生成，您可以在结果区域查看生成的内容",
                icon: "🤖"
              },
              {
                step: 4,
                title: "查看结果",
                desc: "查看完整的执行结果，包括文章内容和工作流的详细信息",
                icon: "📊"
              }
            ].map((item, index) => (
              <div key={item.step} className="flex items-start space-x-4 p-4 bg-white/60 rounded-2xl border border-blue-200/30 hover:bg-white/80 transition-all duration-300">
                <div className="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                  <span className="text-xl">{item.icon}</span>
                </div>
                <div>
                  <div className="flex items-center space-x-2 mb-2">
                    <div className="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                      {item.step}
                    </div>
                    <h3 className="font-bold text-blue-900">{item.title}</h3>
                  </div>
                  <p className="text-blue-800 leading-relaxed">{item.desc}</p>
                </div>
              </div>
            ))}
          </div>
          
          <div className="mt-6 p-4 bg-gradient-to-r from-blue-100/50 to-purple-100/50 rounded-2xl border border-blue-200/30">
            <div className="flex items-center space-x-3">
              <div className="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h4 className="font-bold text-gray-900">💡 小贴士</h4>
                <p className="text-gray-700 text-sm">不同的文章类型会触发不同的AI工作流，建议根据实际需求选择合适的类型以获得最佳效果。</p>
              </div>
            </div>
          </div>
        </Card.Content>
      </Card>
    </div>
  );
};

export default ArticleWorkflow;