import React from 'react';

const Loading = ({ 
  size = 'md', 
  text = '加载中...', 
  className = '',
  variant = 'primary'
}) => {
  const sizes = {
    sm: 'w-4 h-4',
    md: 'w-6 h-6',
    lg: 'w-8 h-8',
    xl: 'w-12 h-12',
  };

  const variants = {
    primary: 'border-blue-600',
    secondary: 'border-gray-600',
    white: 'border-white',
  };

  return (
    <div className={`flex items-center justify-center space-x-3 ${className}`}>
      <div className={`${sizes[size]} border-2 ${variants[variant]} border-t-transparent rounded-full animate-spin`}></div>
      {text && <span className="text-sm text-gray-600">{text}</span>}
    </div>
  );
};

const LoadingScreen = ({ text = '正在加载...' }) => (
  <div className="flex flex-col items-center justify-center h-64 space-y-4">
    <div className="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
    <p className="text-gray-600">{text}</p>
  </div>
);

const LoadingSkeleton = ({ className = '', lines = 3 }) => (
  <div className={`animate-pulse ${className}`}>
    <div className="space-y-3">
      {Array.from({ length: lines }).map((_, i) => (
        <div key={i} className="h-4 bg-gray-200 rounded"></div>
      ))}
    </div>
  </div>
);

Loading.Screen = LoadingScreen;
Loading.Skeleton = LoadingSkeleton;

export default Loading;