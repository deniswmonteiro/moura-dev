import { COLORS, MONTHS, renderChart, chartLineOptions } from "./chart.js";

const ctxChartAwardingSellIn = document.getElementById(
  "chart-awarding-sell-in"
);
const ctxChartAwardingSellOut = document.getElementById(
  "chart-awarding-sell-out"
);

// chart awarding sell in
const configChartAwardingSellIn = {
  type: "line",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "JAN/23",
        data: [5, 7, 14, 15, 20, 23, 28, 30],
        borderWidth: 4,
        borderColor: COLORS.yellow,
        backgroundColor: "rgba(255, 250, 242, 0.5)",
        pointBackgroundColor: COLORS.white,
        pointBorderWidth: 1,
        pointBorderColor: COLORS.yellow,
        fill: true,
      },
      {
        label: "JAN/22",
        data: [5, 9, 17, 16, 19, 25, 29, 25],
        borderWidth: 1,
        borderColor: COLORS.gray,
        backgroundColor: COLORS.gray,
        pointBackgroundColor: COLORS.white,
        pointBorderWidth: 1,
        pointBorderColor: COLORS.gray,
      },
    ],
  },
  options: chartLineOptions,
};

const chartAwardingSellIn = renderChart(
  ctxChartAwardingSellIn,
  configChartAwardingSellIn
);

// chart awarding sell out
const configChartAwardingSellOut = {
  type: "line",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "JAN/23",
        data: [5, 7, 14, 15, 20, 23, 28, 30],
        borderWidth: 4,
        borderColor: COLORS.yellow,
        backgroundColor: "rgba(255, 250, 242, 0.5)",
        pointBackgroundColor: COLORS.white,
        pointBorderWidth: 1,
        pointBorderColor: COLORS.yellow,
        fill: true,
      },
      {
        label: "JAN/22",
        data: [5, 9, 17, 16, 19, 25, 29, 25],
        borderWidth: 1,
        borderColor: COLORS.gray,
        backgroundColor: COLORS.gray,
        pointBackgroundColor: COLORS.white,
        pointBorderWidth: 1,
        pointBorderColor: COLORS.gray,
      },
    ],
  },
  options: chartLineOptions,
};

const chartAwardingSellOut = renderChart(
  ctxChartAwardingSellOut,
  configChartAwardingSellOut
);

window.submitAsideFilter = () => {}