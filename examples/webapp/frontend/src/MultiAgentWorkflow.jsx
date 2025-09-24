import React, { useState, useRef } from 'react';

const MultiAgentWorkflow = () => {
  const [formData, setFormData] = useState({
    workflow_type: 'simple',
    task: 'Research ways to improve energy efficiency in data centers',
    deepseek_key: '',
    qwen_key: ''
  });
  const [events, setEvents] = useState([]);
  const [isStreaming, setIsStreaming] = useState(false);
  const [error, setError] = useState(null);
  const eventSourceRef = useRef(null);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const startStreaming = async () => {
    setIsStreaming(true);
    setError(null);
    setEvents([]);

    try {
      // Using fetch with streaming instead of EventSource for POST requests
      const response = await fetch('http://localhost:8000/api/multi-agent/stream', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const reader = response.body.getReader();
      const decoder = new TextDecoder();

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        const chunk = decoder.decode(value, { stream: true });
        // Process Server-Sent Events format
        const lines = chunk.split('\n\n');
        
        for (const line of lines) {
          if (line.startsWith('data: ')) {
            try {
              const data = JSON.parse(line.slice(6));
              setEvents(prev => [...prev, data]);
              
              if (data.status === 'finished') {
                setIsStreaming(false);
              }
            } catch (err) {
              console.error('Error parsing event data:', err);
            }
          }
        }
      }
    } catch (err) {
      console.error('Streaming error:', err);
      setError('Error connecting to the workflow stream: ' + err.message);
      setIsStreaming(false);
    }
  };

  const stopStreaming = () => {
    if (eventSourceRef.current) {
      eventSourceRef.current.close();
    }
    setIsStreaming(false);
  };

  const clearEvents = () => {
    setEvents([]);
  };

  return (
    <div className="max-w-6xl mx-auto p-6 space-y-8">
      {/* Header */}
      <div className="relative bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl p-8 border border-purple-200/50 shadow-strong overflow-hidden">
        {/* Background decoration */}
        <div className="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-br from-purple-200/20 to-blue-200/15 rounded-full blur-2xl"></div>
        <div className="absolute -bottom-2 -left-2 w-24 h-24 bg-gradient-to-tr from-blue-200/15 to-indigo-200/10 rounded-full blur-xl"></div>
        
        <div className="relative flex items-center space-x-6">
          <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-3xl flex items-center justify-center shadow-xl">
            <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <div>
            <h1 className="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
              Multi-Agent Workflow Lab
            </h1>
            <p className="text-purple-700 mt-2 text-lg">智能多代理协作工作流实验室</p>
            <div className="flex items-center space-x-4 mt-3">
              <div className="flex items-center space-x-2">
                <div className="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span className="text-sm text-purple-600 font-medium">系统在线</span>
              </div>
              <div className="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                🤖 AI 协作
              </div>
            </div>
          </div>
        </div>
      </div>
      
      {/* Configuration Form */}
      <div className="relative bg-gradient-to-br from-white to-purple-50/30 rounded-3xl p-8 border border-purple-200/50 shadow-strong overflow-hidden">
        {/* Background decoration */}
        <div className="absolute -top-2 -right-2 w-24 h-24 bg-gradient-to-br from-purple-200/15 to-blue-200/10 rounded-full blur-xl"></div>
        <div className="absolute -bottom-1 -left-1 w-20 h-20 bg-gradient-to-tr from-blue-200/10 to-indigo-200/5 rounded-full blur-lg"></div>
        
        <div className="relative">
          <div className="flex items-center space-x-4 mb-8">
            <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <h3 className="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                工作流配置
              </h3>
              <p className="text-purple-600 mt-1">配置您的多代理协作工作流</p>
            </div>
          </div>
          
          <form className="space-y-8">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {/* Left Column */}
              <div className="space-y-6">
                <div className="group">
                  <label className="block text-sm font-semibold text-purple-700 mb-3">
                    工作流类型
                  </label>
                  <div className="relative">
                    <select
                      name="workflow_type"
                      value={formData.workflow_type}
                      onChange={handleChange}
                      className="w-full pl-4 pr-12 py-4 text-base border-2 border-purple-200 rounded-2xl bg-white/80 backdrop-blur-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 group-hover:border-purple-300"
                    >
                      <option value="simple">简单工作流</option>
                      <option value="advanced">高级工作流</option>
                    </select>
                    <div className="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                      <svg className="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>
                </div>

                <div className="group">
                  <label className="block text-sm font-semibold text-purple-700 mb-3">
                    任务描述
                  </label>
                  <textarea
                    name="task"
                    value={formData.task}
                    onChange={handleChange}
                    rows={5}
                    className="w-full px-4 py-4 text-base border-2 border-purple-200 rounded-2xl bg-white/80 backdrop-blur-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 group-hover:border-purple-300 resize-none"
                    placeholder="详细描述您希望多代理系统完成的任务..."
                  />
                </div>
              </div>

              {/* Right Column */}
              <div className="space-y-6">
                <div className="group">
                  <label className="block text-sm font-semibold text-purple-700 mb-3">
                    DeepSeek API 密钥
                  </label>
                  <input
                    type="password"
                    name="deepseek_key"
                    value={formData.deepseek_key}
                    onChange={handleChange}
                    className="w-full px-4 py-4 text-base border-2 border-purple-200 rounded-2xl bg-white/80 backdrop-blur-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 group-hover:border-purple-300"
                    placeholder="输入您的 DeepSeek API 密钥"
                  />
                </div>

                <div className="group">
                  <label className="block text-sm font-semibold text-purple-700 mb-3">
                    Qwen API 密钥
                  </label>
                  <input
                    type="password"
                    name="qwen_key"
                    value={formData.qwen_key}
                    onChange={handleChange}
                    className="w-full px-4 py-4 text-base border-2 border-purple-200 rounded-2xl bg-white/80 backdrop-blur-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 group-hover:border-purple-300"
                    placeholder="输入您的 Qwen API 密钥"
                  />
                </div>

                {/* Action Buttons */}
                <div className="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-200/50">
                  <h4 className="text-lg font-semibold text-purple-700 mb-4">操作控制</h4>
                  <div className="space-y-3">
                    <button
                      type="button"
                      onClick={isStreaming ? stopStreaming : startStreaming}
                      disabled={!formData.task || (!formData.deepseek_key && !formData.qwen_key)}
                      className="w-full group relative overflow-hidden bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold py-4 px-6 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-[1.02] transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    >
                      <div className="absolute inset-0 bg-gradient-to-r from-purple-700 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                      <div className="relative flex items-center justify-center space-x-2">
                        {isStreaming ? (
                          <>
                            <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>停止工作流</span>
                          </>
                        ) : (
                          <>
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h1m4 0h1M9 6h6" />
                            </svg>
                            <span>启动工作流</span>
                          </>
                        )}
                      </div>
                    </button>
                    
                    <button
                      type="button"
                      onClick={clearEvents}
                      className="w-full group bg-white border-2 border-gray-200 text-gray-600 font-semibold py-3 px-4 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-300"
                    >
                      <div className="flex items-center justify-center space-x-2">
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>清除事件</span>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      {/* Error Display */}
      {error && (
        <div className="relative bg-gradient-to-br from-red-50 to-pink-50 rounded-3xl p-6 border border-red-200/50 shadow-strong overflow-hidden">
          <div className="absolute -top-2 -right-2 w-20 h-20 bg-gradient-to-br from-red-200/20 to-pink-200/15 rounded-full blur-xl"></div>
          
          <div className="relative flex items-start space-x-4">
            <div className="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
            <div className="flex-1">
              <h3 className="text-lg font-semibold text-red-700 mb-2">系统错误</h3>
              <p className="text-red-600 leading-relaxed">{error}</p>
            </div>
          </div>
        </div>
      )}

      {/* Workflow Events */}
      <div className="relative bg-gradient-to-br from-white to-gray-50/30 rounded-3xl p-8 border border-gray-200/50 shadow-strong overflow-hidden">
        {/* Background decoration */}
        <div className="absolute -top-2 -right-2 w-24 h-24 bg-gradient-to-br from-gray-200/15 to-blue-200/10 rounded-full blur-xl"></div>
        <div className="absolute -bottom-1 -left-1 w-20 h-20 bg-gradient-to-tr from-blue-200/10 to-indigo-200/5 rounded-full blur-lg"></div>
        
        <div className="relative">
          <div className="flex items-center justify-between mb-8">
            <div className="flex items-center space-x-4">
              <div className="w-12 h-12 bg-gradient-to-br from-gray-600 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <div>
                <h3 className="text-2xl font-bold bg-gradient-to-r from-gray-700 to-blue-600 bg-clip-text text-transparent">
                  工作流事件
                </h3>
                <p className="text-gray-600 mt-1">实时监控多代理协作过程</p>
              </div>
            </div>
            
            {events.length > 0 && (
              <div className="flex items-center space-x-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-xl">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span className="text-sm font-medium">{events.length} 个事件</span>
              </div>
            )}
          </div>
          
          <div className="space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
            {events.length === 0 ? (
              <div className="text-center py-16">
                <div className="w-20 h-20 bg-gradient-to-br from-gray-100 to-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                  <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                </div>
                <h4 className="text-lg font-semibold text-gray-700 mb-2">暂无事件</h4>
                <p className="text-gray-500">启动工作流后，事件将在此处显示</p>
              </div>
            ) : (
              <div className="flow-root">
                <ul className="divide-y divide-gray-200/50">
                  {events.map((event, index) => (
                    <li key={index} className="py-6">
                      <div className="group relative bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl p-6 hover:shadow-lg transition-all duration-300">
                        <div className="flex items-start space-x-4">
                          <div className="flex-shrink-0">
                            {event.status === 'started' && (
                              <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h1m4 0h1M9 6h6" />
                                </svg>
                              </div>
                            )}
                            {event.status === 'processing' && (
                              <div className="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg className="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                              </div>
                            )}
                            {event.status === 'completed' && (
                              <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                              </div>
                            )}
                            {event.status === 'error' && (
                              <div className="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                              </div>
                            )}
                          </div>
                          
                          <div className="flex-1 min-w-0">
                            <div className="flex items-center justify-between mb-3">
                              <div className="flex items-center space-x-3">
                                <h4 className="text-lg font-semibold text-gray-800 capitalize">
                                  {event.status === 'started' && '已开始'}
                                  {event.status === 'processing' && '处理中'}
                                  {event.status === 'completed' && '已完成'}
                                  {event.status === 'error' && '错误'}
                                </h4>
                                <span className="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                                  {event.status}
                                </span>
                              </div>
                              <div className="flex items-center space-x-2 text-sm text-gray-500">
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{new Date().toLocaleTimeString()}</span>
                              </div>
                            </div>
                            
                            <p className="text-gray-700 leading-relaxed mb-3">
                              {event.message || event.step || (event.progress && `进度: ${event.progress}`)}
                            </p>
                            
                            {event.result && (
                              <div className="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200/50">
                                <div className="flex items-center space-x-2 mb-2">
                                  <svg className="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                  </svg>
                                  <span className="text-sm font-medium text-gray-700">执行结果</span>
                                </div>
                                <div className="space-y-2">
                                  {event.result.task && (
                                    <p className="text-sm text-gray-600"><strong>任务:</strong> {event.result.task}</p>
                                  )}
                                  {(event.result.summary || event.result.executive_summary) && (
                                    <p className="text-sm text-gray-600"><strong>摘要:</strong> {event.result.summary || event.result.executive_summary}</p>
                                  )}
                                </div>
                              </div>
                            )}
                          </div>
                        </div>
                      </div>
                    </li>
                  ))}
                </ul>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default MultiAgentWorkflow;