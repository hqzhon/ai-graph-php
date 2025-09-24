import React, { useState } from 'react';

// --- Helper Components ---

const FormInput = ({ label, name, value, onChange, type = 'text', placeholder = '' }) => (
  <div className="group">
    <label htmlFor={name} className="block text-sm font-semibold text-blue-900 mb-2">{label}</label>
    <input
      type={type}
      name={name}
      id={name}
      value={value}
      onChange={onChange}
      placeholder={placeholder}
      className="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-blue-200/50 rounded-xl shadow-soft focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all duration-300 group-hover:border-blue-300/70"
    />
  </div>
);

const FormTextarea = ({ label, name, value, onChange, rows = 4 }) => (
  <div className="group">
    <label htmlFor={name} className="block text-sm font-semibold text-blue-900 mb-2">{label}</label>
    <textarea
      name={name}
      id={name}
      value={value}
      onChange={onChange}
      rows={rows}
      className="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-blue-200/50 rounded-xl shadow-soft focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all duration-300 group-hover:border-blue-300/70 resize-none"
    />
  </div>
);

const FormSelect = ({ label, name, value, onChange, children }) => (
  <div className="group">
    <label htmlFor={name} className="block text-sm font-semibold text-blue-900 mb-2">{label}</label>
    <select
      name={name}
      id={name}
      value={value}
      onChange={onChange}
      className="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-blue-200/50 rounded-xl shadow-soft focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all duration-300 group-hover:border-blue-300/70 appearance-none cursor-pointer"
    >
      {children}
    </select>
  </div>
);

const SubmitButton = ({ isLoading }) => (
    <button
        type="submit"
        disabled={isLoading}
        className="w-full h-14 bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600 hover:from-blue-600 hover:via-indigo-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-strong hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed group"
    >
        <span className="flex items-center justify-center space-x-3 group-hover:scale-105 transition-transform duration-200">
            {isLoading ? (
                <>
                    <svg className="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span className="text-lg">测试中...</span>
                </>
            ) : (
                <>
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span className="text-lg">开始测试</span>
                </>
            )}
        </span>
    </button>
);

const ResultCard = ({ result }) => (
    <div className="relative overflow-hidden bg-gradient-to-br from-white/90 via-green-50/50 to-emerald-50/30 backdrop-blur-sm rounded-3xl border border-green-200/50 shadow-strong">
        {/* Background decoration */}
        <div className="absolute -top-6 -right-6 w-40 h-40 bg-gradient-to-br from-green-200/30 to-emerald-200/20 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-tr from-emerald-200/15 to-teal-200/10 rounded-full blur-2xl"></div>
        
        <div className="relative">
            <div className="p-8 border-b border-green-200/30">
                <div className="flex items-center space-x-4">
                    <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-strong">
                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 className="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            测试结果
                        </h3>
                        <div className="flex items-center space-x-2 mt-1">
                            <div className="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                成功
                            </div>
                            <span className="text-green-700/80 text-sm">执行完成</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div className="p-8 space-y-6">
                <div className="group">
                    <h4 className="text-sm font-semibold text-green-900 mb-3 flex items-center space-x-2">
                        <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>模型类型</span>
                    </h4>
                    <div className="bg-white/80 backdrop-blur-sm border border-green-200/50 rounded-xl p-4 shadow-soft group-hover:shadow-md transition-all duration-300">
                        <p className="text-green-900 font-mono text-lg font-semibold">{result.model_type}</p>
                    </div>
                </div>
                
                <div className="group">
                    <h4 className="text-sm font-semibold text-green-900 mb-3 flex items-center space-x-2">
                        <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span>输入提示</span>
                    </h4>
                    <div className="bg-white/80 backdrop-blur-sm border border-green-200/50 rounded-xl p-4 shadow-soft group-hover:shadow-md transition-all duration-300">
                        <p className="text-gray-700 leading-relaxed">{result.prompt}</p>
                    </div>
                </div>
                
                <div className="group">
                    <h4 className="text-sm font-semibold text-green-900 mb-3 flex items-center space-x-2">
                        <div className="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span>模型响应</span>
                    </h4>
                    <div className="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 shadow-strong group-hover:shadow-xl transition-all duration-300 border border-gray-700/50">
                        <pre className="text-green-400 font-mono text-sm leading-relaxed whitespace-pre-wrap overflow-auto max-h-64">
                            {result.response}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
);

const ErrorAlert = ({ error }) => (
    <div className="relative overflow-hidden bg-gradient-to-br from-red-50/90 via-rose-50/50 to-pink-50/30 backdrop-blur-sm rounded-3xl border border-red-200/50 shadow-strong">
        {/* Background decoration */}
        <div className="absolute -top-6 -right-6 w-40 h-40 bg-gradient-to-br from-red-200/30 to-rose-200/20 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-tr from-rose-200/15 to-pink-200/10 rounded-full blur-2xl"></div>
        
        <div className="relative p-8">
            <div className="flex items-start space-x-4">
                <div className="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-strong flex-shrink-0">
                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div className="flex-1 min-w-0">
                    <div className="flex items-center space-x-2 mb-3">
                        <h3 className="text-xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">
                            执行错误
                        </h3>
                        <div className="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                            失败
                        </div>
                    </div>
                    <div className="bg-white/80 backdrop-blur-sm border border-red-200/50 rounded-xl p-4 shadow-soft">
                        <p className="text-red-800 leading-relaxed break-words">{error}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
);

// --- Main Component ---

const ModelTest = () => {
  const [formData, setFormData] = useState({
    model_type: 'deepseek',
    prompt: 'Hello, what is the capital of France?',
    model_name: '',
    deepseek_key: '',
    qwen_key: ''
  });
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setResult(null);

    try {
      const response = await fetch('http://localhost:8000/api/model/test', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });
      const data = await response.json();
      if (data.success) {
        setResult(data.data);
      } else {
        setError(data.error || 'An unknown error occurred.');
      }
    } catch (err) {
      setError(err.message || 'Failed to connect to the server.');
    }

    setLoading(false);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 relative overflow-hidden">
      {/* Background decoration */}
      <div className="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-blue-200/20 to-indigo-200/10 rounded-full blur-3xl"></div>
      <div className="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-200/15 to-purple-200/10 rounded-full blur-3xl"></div>
      
      {/* Header */}
      <div className="relative mb-12">
        <div className="flex items-center space-x-6 mb-6">
          <div className="w-16 h-16 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-600 rounded-3xl flex items-center justify-center shadow-strong">
            <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <h1 className="text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
              AI 模型测试器
            </h1>
            <div className="flex items-center space-x-4 mt-2">
              <div className="flex items-center space-x-2">
                <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span className="text-green-600 text-sm font-semibold">系统在线</span>
              </div>
              <div className="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                多模型支持
              </div>
            </div>
          </div>
        </div>
        <p className="text-gray-600 text-lg max-w-3xl">
          测试和比较不同AI模型的性能表现，支持DeepSeek和Qwen等主流模型
        </p>
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
            
            {/* Configuration Form */}
            <div className="lg:col-span-1">
                <div className="relative overflow-hidden bg-gradient-to-br from-white/90 via-blue-50/50 to-indigo-50/30 backdrop-blur-sm rounded-3xl border border-blue-200/50 shadow-strong">
                    {/* Background decoration */}
                    <div className="absolute -top-6 -right-6 w-32 h-32 bg-gradient-to-br from-blue-200/30 to-indigo-200/20 rounded-full blur-2xl"></div>
                    <div className="absolute -bottom-6 -left-6 w-24 h-24 bg-gradient-to-tr from-indigo-200/20 to-purple-200/15 rounded-full blur-xl"></div>
                    
                    <div className="relative p-8">
                        <div className="flex items-center space-x-4 mb-8">
                            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-strong">
                                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                                </svg>
                            </div>
                            <div>
                                <h2 className="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                    模型配置
                                </h2>
                                <p className="text-blue-700/80 mt-1">设置测试参数</p>
                            </div>
                        </div>
                        
                        <form onSubmit={handleSubmit} className="space-y-6">
                        <FormSelect label="Model Type" name="model_type" value={formData.model_type} onChange={handleChange}>
                            <option value="deepseek">DeepSeek</option>
                            <option value="qwen">Qwen</option>
                        </FormSelect>

                        <FormTextarea label="Prompt" name="prompt" value={formData.prompt} onChange={handleChange} />

                        <FormInput label="Model Name (Optional)" name="model_name" value={formData.model_name} onChange={handleChange} />
                        
                        <div className="border-t pt-6 space-y-4">
                            <p className="text-sm text-gray-500">API Keys (Optional)</p>
                            <FormInput label="DeepSeek API Key" name="deepseek_key" value={formData.deepseek_key} onChange={handleChange} type="password" />
                            <FormInput label="Qwen API Key" name="qwen_key" value={formData.qwen_key} onChange={handleChange} type="password" />
                        </div>

                            <SubmitButton isLoading={loading} />
                        </form>
                    </div>
                </div>
            </div>

            {/* Result Display */}
            <div className="lg:col-span-2">
                {error && <ErrorAlert error={error} />}
                {result && <ResultCard result={result} />}
                {!error && !result && (
                    <div className="relative overflow-hidden bg-gradient-to-br from-white/90 via-gray-50/50 to-blue-50/30 backdrop-blur-sm rounded-3xl border border-gray-200/50 shadow-strong">
                        {/* Background decoration */}
                        <div className="absolute -top-6 -right-6 w-40 h-40 bg-gradient-to-br from-gray-200/20 to-blue-200/10 rounded-full blur-3xl"></div>
                        <div className="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-tr from-blue-200/15 to-indigo-200/10 rounded-full blur-2xl"></div>
                        
                        <div className="relative flex flex-col items-center justify-center h-96 text-center p-12">
                            <div className="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-500 rounded-3xl flex items-center justify-center mb-6 shadow-strong">
                                <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-bold text-gray-700 mb-3">准备就绪</h3>
                            <p className="text-gray-500 text-lg max-w-md">
                                配置模型参数并运行测试，结果将在这里显示
                            </p>
                            <div className="mt-6 flex items-center space-x-2 text-sm text-gray-400">
                                <div className="w-2 h-2 bg-gray-300 rounded-full animate-pulse"></div>
                                <span>等待测试开始...</span>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    </div>
  );
};

export default ModelTest;
