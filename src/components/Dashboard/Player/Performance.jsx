export function PlayerPerformance() {
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Performance Stats</h2>
        <div className="space-y-2">
          <p>Speed: <span className="font-semibold">7.8 m/s</span></p>
          <p>Goals: <span className="font-semibold">14</span></p>
          <p>Assists: <span className="font-semibold">9</span></p>
          <p>Attendance: <span className="font-semibold">95%</span></p>
        </div>
      </section>
    );
  }